<?php

declare(strict_types = 1);

namespace Tests;

use \PHPUnit\Framework\TestCase;

use \Reflection\ReflectionUseStatements;
use \Reflection\UseStatements;
use \Reflection\UseStatement;

use \Tests\Dummy\Tree;

/**
 * Class ClassUseStatementsTest
 * @package Tests
 */
class ReflectionUseStatementsTest extends TestCase {

    public function testMain() {
        $reflection = new ReflectionUseStatements(Tree::class);

        $this->assertEquals(
            $reflection->getUseStatements(),
            (new UseStatements())
                ->add(new UseStatement('\JsonSerializable'))
                ->add(new UseStatement('\Tests\Dummy\Branch', 'BranchClass'))
                ->add(new UseStatement('\Tests\Dummy\Leaf'))
        );

        $this->assertFalse($reflection->isNotUserDefined());

        $this->assertTrue($reflection->hasUseStatement('\JsonSerializable'));
        $this->assertTrue($reflection->hasUseStatement('JsonSerializable'));
        $this->assertTrue($reflection->hasUseStatement('\Tests\Dummy\Branch'));
        $this->assertTrue($reflection->hasUseStatement('BranchClass'));
        $this->assertTrue($reflection->hasUseStatement('\Tests\Dummy\Leaf'));
        $this->assertTrue($reflection->hasUseStatement('Leaf'));
        $this->assertFalse($reflection->hasUseStatement('LeafClass'));

        $this->assertNull($reflection->getUseStatements()->getClass('LeafClass'));
        $this->assertEquals(
            $reflection->getUseStatements()->getClass('BranchClass'),
            new UseStatement('\Tests\Dummy\Branch', 'BranchClass')
        );

        $this->expectException('RuntimeException');
        $this->expectExceptionMessage('Can get use statements from user defined classes only.');
        (new ReflectionUseStatements('\JsonSerializable'))->getUseStatements();
    }

}