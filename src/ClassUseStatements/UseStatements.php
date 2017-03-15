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

}