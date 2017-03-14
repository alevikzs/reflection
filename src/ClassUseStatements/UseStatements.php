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
     * @param string $class
     * @return string|null
     */
    public function getFullClassName(string $class): ?string {
        return null;
    }

}