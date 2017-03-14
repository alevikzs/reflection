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
                ->add(new UseStatement('\JsonSerializable'))
                ->add(new UseStatement('\Reflection\Tests\Dummy\Branch', 'BranchClass'))
                ->add(new UseStatement('\Reflection\Tests\Dummy\Leaf'))
        );

        $this->assertFalse($reflection->isNotUserDefined());

        $this->assertTrue($reflection->hasUseStatement('\JsonSerializable'));
        $this->assertTrue($reflection->hasUseStatement('JsonSerializable'));
        $this->assertTrue($reflection->hasUseStatement('\Reflection\Tests\Dummy\Branch'));
        $this->assertTrue($reflection->hasUseStatement('BranchClass'));
        $this->assertTrue($reflection->hasUseStatement('\Reflection\Tests\Dummy\Leaf'));
        $this->assertTrue($reflection->hasUseStatement('Leaf'));
        $this->assertFalse($reflection->hasUseStatement('LeafClass'));

        $this->assertNull($reflection->getUseStatements()->findUseStatement('LeafClass'));
        $this->assertEquals(
            $reflection->getUseStatements()->findUseStatement('BranchClass'),
            new UseStatement('\Reflection\Tests\Dummy\Branch', 'BranchClass')
        );

        $this->expectException('RuntimeException');
        $this->expectExceptionMessage('Can get use statements from user defined classes only.');
        (new ClassUseStatements('\JsonSerializable'))->getUseStatements();
    }

}