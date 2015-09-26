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

use Gitlab\Utils\StringUtil;
use PHPUnit_Framework_TestCase;

class StringUtilTest extends PHPUnit_Framework_TestCase
{
    public function testEndsWith()
    {
        $this->assertTrue(StringUtil::endsWith('Hello World', ''));
        $this->assertTrue(StringUtil::endsWith('Hello World', 'd'));
        $this->assertTrue(StringUtil::endsWith('Hello World', 'World'));
        $this->assertTrue(StringUtil::endsWith('Hello World', 'Hello World'));
        $this->assertFalse(StringUtil::endsWith('Hello World', 'Hello'));
        $this->assertFalse(StringUtil::endsWith('Hello World', 'Hello Worl'));
    }

    public function testStartsWith()
    {
        $this->assertTrue(StringUtil::startsWith('Hello World', 'H'));
        $this->assertTrue(StringUtil::startsWith('Hello World', 'Hello'));
        $this->assertTrue(StringUtil::startsWith('Hello World', 'Hello World'));
        $this->assertFalse(StringUtil::startsWith('Hello World', 'World'));
        $this->assertFalse(StringUtil::startsWith('Hello World', ' Hello'));
    }
}
