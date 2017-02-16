<?php

declare(strict_types = 1);

namespace Tests;

use \PHPUnit_Framework_TestCase as TestCase;
use \UsesReflection\ReflectionClassUses;
use \Tests\Dummy\Tree;

/**
 * Class ClassParserTest
 * @package Tests
 */
class ClassParserTest extends TestCase {

    public function testMain() {
        $reflectionClassUses = new ReflectionClassUses(Tree::class);

        $uses = $reflectionClassUses->getUseStatements();

        $this->assertEquals($uses, [
            [
                'class' => '\JsonSerializable',
                'as' => 'Serializable',
            ],
            [
                'class' => '\Tests\Dummy\Branch',
                'as' => 'BranchClass',
            ]
        ]);
    }

}