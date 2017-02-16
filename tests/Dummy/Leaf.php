<?php

declare(strict_types = 1);

namespace Tests\Dummy;

use \JsonSerializable as Serializable;

/**
 * Class Leaf
 * @package Tests\Dummy
 */
class Leaf implements Serializable {

    /**
     * @var double
     */
    private $height;

    /**
     * @var double
     */
    private $width;

    /**
     * @return float
     */
    public function getHeight() {
        return $this->height;
    }

    /**
     * @param float $height
     * @return $this
     */
    public function setHeight($height) {
        $this->height = $height;

        return $this;
    }

    /**
     * @return float
     */
    public function getWidth() {
        return $this->width;
    }

    /**
     * @param float $width
     * @return $this
     */
    public function setWidth($width) {
        $this->width = $width;

        return $this;
    }

    /**
     * @param double $height
     * @param double $width
     */
    public function __construct($height = null, $width = null) {
        $this->height = $height;
        $this->width = $width;
    }

    /**
     * @return array
     */
    public function jsonSerialize() {
        return [
            'height' => $this->getHeight(),
            'width' => $this->getWidth()
        ];
    }

}