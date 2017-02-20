##Use Statements Reflection API

[![License](https://poser.pugx.org/alevikzs/reflection/license)](https://packagist.org/packages/alevikzs/reflection)
[![Latest Stable Version](https://poser.pugx.org/alevikzs/reflection/v/stable)](https://packagist.org/packages/alevikzs/reflection) 
[![Total Downloads](https://poser.pugx.org/alevikzs/reflection/downloads)](https://packagist.org/packages/alevikzs/reflection) 
[![Reference Status](https://www.versioneye.com/php/alevikzs:reflection/reference_badge.svg?style=flat)](https://www.versioneye.com/php/alevikzs:reflection/references)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/alevikzs/reflection/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/alevikzs/reflection/?branch=master)
[![Code Climate](https://codeclimate.com/github/alevikzs/reflection/badges/gpa.svg)](https://codeclimate.com/github/alevikzs/reflection)
[![Build Status](https://secure.travis-ci.org/alevikzs/reflection.png?branch=master)](http://travis-ci.org/alevikzs/reflection)

###About
This package is provide reflection API for retrieve use statements by some class.

###Requirements
* PHP >= 7.0

###Installation##
Require the package with composer: ```$ composer require alevikzs/reflection```

###How to use
First, you need to create reflection object:
```
$reflection = new ReflectionUseStatements(Tree::class);
```
and then you can get use statements or check if some class was used:
```
$reflection->getUseStatements();
$reflection->hasUseStatement(Tree::class)
```

###MIT License
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