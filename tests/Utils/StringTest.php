<?php
/*
 * This file is part of fgrosse/gitlab-api.
 *
 * Copyright © Friedrich Große <friedrich.grosse@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gitlab\Test\Utils;

use Gitlab\Utils\String;
use PHPUnit_Framework_TestCase;

class StringTest extends PHPUnit_Framework_TestCase
{
    public function testEndsWith()
    {
        $this->assertTrue(String::endsWith('Hello World', ''));
        $this->assertTrue(String::endsWith('Hello World', 'd'));
        $this->assertTrue(String::endsWith('Hello World', 'World'));
        $this->assertTrue(String::endsWith('Hello World', 'Hello World'));
        $this->assertFalse(String::endsWith('Hello World', 'Hello'));
        $this->assertFalse(String::endsWith('Hello World', 'Hello Worl'));
    }

    public function testStartsWith()
    {
        $this->assertTrue(String::startsWith('Hello World', 'H'));
        $this->assertTrue(String::startsWith('Hello World', 'Hello'));
        $this->assertTrue(String::startsWith('Hello World', 'Hello World'));
        $this->assertFalse(String::startsWith('Hello World', 'World'));
        $this->assertFalse(String::startsWith('Hello World', ' Hello'));
    }
}
