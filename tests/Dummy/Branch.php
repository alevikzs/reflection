<?php

declare(strict_types = 1);

namespace Reflection\Tests\Dummy;

use \JsonSerializable as Serializable;
use \Reflection\Tests\Dummy\Leaf as LeafClass;

/**
 * Class Branch
 * @package Reflection\Tests\Dummy
 */
class Branch implements Serializable {

    /**
     * @var double
     */
    private $length;

    /**
     * @var LeafClass[]
     */
    private $leaves;

    /**
     * @return float
     */
    public function getLength(): float {
        return $this->length;
    }

    /**
     * @param float $length
     * @return Branch
     */
    public function setLength(float $length): Branch {
        $this->length = $length;

        return $this;
    }

    /**
     * @return LeafClass[]
     */
    public function getLeaves(): array {
        return $this->leaves;
    }

    /**
     * @param LeafClass[] $leaves
     * @return Branch
     */
    public function setLeaves(array $leaves = []): Branch {
        $this->leaves = $leaves;

        return $this;
    }

    /**
     * @param float $length
     * @param LeafClass[] $leaves
     */
    public function __construct(float $length = null, array $leaves = []) {
        $this->length = $length;
        $this->leaves = $leaves;
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array {
        return [
            'length' => $this->getLength(),
            'leaves' => $this->getLeaves()
        ];
    }

}