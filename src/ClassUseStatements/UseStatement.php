<?php

declare(strict_types = 1);

namespace Reflection\ClassUseStatements;

/**
 * Class UseStatement
 * @package UsesReflection
 */
class UseStatement {

    /**
     * @var string
     */
    private $use;

    /**
     * @var string
     */
    private $alias;

    /**
     * UseStatement constructor.
     * @param string $use
     * @param string $alias
     */
    public function __construct(string $use, string $alias = '') {
        $this->setUse($use)
            ->setAlias($alias);
    }

    /**
     * @return string
     */
    public function getUse(): string {
        return $this->use;
    }

    /**
     * @param string $use
     * @return UseStatement
     */
    public function setUse(string $use): UseStatement {
        if ($use[0] !== '\\') {
            $use = "\\$use";
        }

        $this->use = $use;

        return $this;
    }

    /**
     * @return string
     */
    public function getAlias(): string {
        return $this->alias;
    }

    /**
     * @param string $alias
     * @return UseStatement
     */
    public function setAlias(string $alias): UseStatement {
        $this->alias = $alias;

        return $this;
    }

    /**
     * @param string $statement
     * @return bool
     */
    public function isEqual(string $statement): bool {
        if ($statement === $this->getUse()) {
            return true;
        }

        if ($statement === $this->getAlias()) {
            return true;
        }

        return false;
    }

}