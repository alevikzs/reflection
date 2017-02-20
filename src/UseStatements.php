<?php

declare(strict_types = 1);

namespace Reflection;

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
     * @param string $class
     * @return bool
     */
    public function hasClass(string $class): bool {
        /** @var UseStatement $useStatement */
        foreach ($this as $useStatement) {
            if (in_array($class, $useStatement->toArray())) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param string $class
     * @return UseStatement|null
     */
    public function getClass(string $class): ?UseStatement {
        /** @var UseStatement $useStatement */
        foreach ($this as $useStatement) {
            if (in_array($class, $useStatement->toArray())) {
                return $useStatement;
            }
        }

        return null;
    }

}