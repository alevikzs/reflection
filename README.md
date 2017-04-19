## Use Statements Reflection API

[![License](http://poser.pugx.org/alevikzs/reflection/license)](https://packagist.org/packages/alevikzs/reflection)
[![Latest Stable Version](http://poser.pugx.org/alevikzs/reflection/v/stable)](https://packagist.org/packages/alevikzs/reflection) 
[![Total Downloads](http://poser.pugx.org/alevikzs/reflection/downloads)](https://packagist.org/packages/alevikzs/reflection) 
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/alevikzs/reflection/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/alevikzs/reflection/?branch=master)
[![Code Climate](https://codeclimate.com/github/alevikzs/reflection/badges/gpa.svg)](https://codeclimate.com/github/alevikzs/reflection)
[![Build Status](https://scrutinizer-ci.com/g/alevikzs/reflection/badges/build.png?b=master)](https://scrutinizer-ci.com/g/alevikzs/reflection/build-status/master)
[![Code Coverage](https://scrutinizer-ci.com/g/alevikzs/reflection/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/alevikzs/reflection/?branch=master)

### About
This package provides reflection API for retrieve use statements by some class. You can parse the next use statements:
```
use \ReflectionClass,
    \ReflectionFunction,
    \ReflectionMethod;
use Reflection\Tests\Dummy\Tree\{Root};
use \Reflection\Tests\Dummy as DummyAlias;
use \ReflectionObject;
use \Reflection\Tests\Dummy\Tree\{Trunk\Branch as BranchAlias, Trunk\Fruit, Trunk\Leaf as LeafAlias};
```

### Requirements
* PHP >= 7.1

### Installation
Require the package with composer: ```$ composer require alevikzs/reflection```

### How to use
First, you need to create reflection object:
```
$reflection = new \Reflection\ClassUseStatements(Tree::class);
```
and then you can get use statements or check if some class was used:
```
$reflection->getUseStatements();
$reflection->hasUseStatement(Branch::class)
```
Also, you can build global(full) class name by local class name that was imported in your class:
```
$reflection->getUseStatements()->getFullClassName('Some\Namespace\Class');
```
The best way to see how it works is to look at tests.

### MIT License
**Copyright (c) 2017 Alexey Novikov <alekseeey@gmail.com>**

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.