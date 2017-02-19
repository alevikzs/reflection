<?php

declare(strict_types = 1);

namespace UsesReflection;

/**
 * Class UseStatement
 * @package UsesReflection
 */
class UseStatement {

    /**
     * @var string
     */
    private $fullClassName;

    /**
     * @var string
     */
    private $className;

    /**
     * @var string
     */
    private $alias;

    /**
     * UseStatement constructor.
     * @param string $fullClassName
     * @param string $alias
     */
    public function __construct(string $fullClassName, string $alias) {
        $this->setFullClassName($fullClassName)
            ->setAlias($alias);
    }

    /**
     * @return string
     */
    public function getFullClassName(): string {
        return $this->fullClassName;
    }

    /**
     * @param string $fullClassName
     * @return UseStatement
     */
    public function setFullClassName(string $fullClassName): UseStatement {
        $this->fullClassName = $fullClassName;

        $paths = explode('\\', $fullClassName);
        $this->className = end($paths);

        return $this;
    }

    /**
     * @return string
     */
    public function getClassName(): string {
        return $this->className;
    }

    /**
     * @param string $className
     * @return UseStatement
     */
    public function setClassName(string $className): UseStatement {
        $this->className = $className;

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
     * @return array
     */
    public function toArray() {
        return [
            $this->getFullClassName(),
            $this->getClassName(),
            $this->getAlias()
        ];
    }

}