<?php

declare(strict_types = 1);

namespace Reflection\Tests;

use \PHPUnit\Framework\TestCase;

use \Reflection\ClassUseStatements;
use \Reflection\ClassUseStatements\UseStatements;
use \Reflection\ClassUseStatements\UseStatement;

use \Reflection\Tests\Dummy\Tree;

/**
 * Class ClassUseStatementsTest
 * @package Reflection\Tests
 */
class ClassUseStatementsTest extends TestCase {

    public function testMain() {
        $reflection = new ClassUseStatements(Tree::class);

        $this->assertEquals(
            $reflection->getUseStatements(),
            (new UseStatements())
                ->add(new UseStatement('\ReflectionClass'))
                ->add(new UseStatement('\ReflectionFunction'))
                ->add(new UseStatement('\ReflectionMethod'))
                ->add(new UseStatement('\Reflection\Tests\Dummy\Tree\Root'))
                ->add(new UseStatement('\Reflection\Tests\Dummy', 'DummyAlias'))
                ->add(new UseStatement('\ReflectionObject'))
                ->add(new UseStatement('\Reflection\Tests\Dummy\Tree\Trunk\Branch', 'BranchAlias'))
                ->add(new UseStatement('\Reflection\Tests\Dummy\Tree\Trunk\Fruit'))
                ->add(new UseStatement('\Reflection\Tests\Dummy\Tree\Trunk\Leaf', 'LeafAlias'))
        );

        $this->assertFalse($reflection->isNotUserDefined());

        $this->assertTrue($reflection->hasUseStatement('\ReflectionClass'));
        $this->assertTrue($reflection->hasUseStatement('LeafAlias'));
        $this->assertFalse($reflection->hasUseStatement('Dummy'));

        $this->expectException('RuntimeException');
        $this->expectExceptionMessage('Can get use statements from user defined classes only.');
        (new ClassUseStatements('\JsonSerializable'))->getUseStatements();
    }

}