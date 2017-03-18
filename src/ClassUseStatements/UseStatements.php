<?php

declare(strict_types = 1);

namespace Reflection\ClassUseStatements;

use \ArrayObject;

/**
 * Class UseStatements
 * @package UsesReflection
 */
class UseStatements extends ArrayObject {

    /**
     * @param UseStatement $useStatement
     * @return UseStatements
     */
    public function add(UseStatement $useStatement): UseStatements {
        $this->append($useStatement);

        return $this;
    }

    /**
     * @param string $statement
     * @return bool
     */
    public function hasStatement(string $statement): bool {
        /** @var UseStatement $useStatement */
        foreach ($this as $useStatement) {
            if ($useStatement->isEqual($statement)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param string $class
     * @return string|null
     */
    public function getFullClassName(string $class): ?string {
        if ($class[0] === '\\') {
            return $class;
        }

        $classParts = explode('\\', $class);
        $passedNamespace = array_shift($classParts);

        /** @var UseStatement $useStatement */
        foreach ($this as $useStatement) {
            $useParts = explode('\\', $useStatement->getUse());
            $definedNamespace = array_pop($useParts);

            if ($definedNamespace === $class || $useStatement->getAlias() === $class) {
                return $useStatement->getUse();
            } elseif ($definedNamespace === $passedNamespace || $useStatement->getAlias() === $passedNamespace) {
                return $useStatement->getUse() . '\\' . implode('\\', $classParts);
            }
        }

        return null;
    }

}