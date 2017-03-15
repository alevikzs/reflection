<?php

namespace Reflection\Tests\Dummy;

use \ReflectionClass,
    \ReflectionFunction,
    \ReflectionMethod;
use Reflection\Tests\Dummy\Tree\{Root};
use \Reflection\Tests\Dummy as DummyAlias;
use \ReflectionObject;
use \Reflection\Tests\Dummy\Tree\{Trunk\Branch as BranchAlias, Trunk\Fruit, Trunk\Leaf as LeafAlias};

/**
 * Class Tree
 * @package Reflection\Tests\Dummy
 */
class Tree {

}