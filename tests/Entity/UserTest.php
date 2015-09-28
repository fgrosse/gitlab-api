<?php
/*
 * This file is part of fgrosse/gitlab-api.
 *
 * Copyright © Friedrich Große <friedrich.grosse@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gitlab\Test\Entity;

use Gitlab\Entity\MergeRequest;
use Gitlab\Entity\User;
use PHPUnit_Framework_TestCase;

class UserTest extends PHPUnit_Framework_TestCase
{
    public function testFromArray()
    {
        $actual = User::fromArray([
            'id'=> 1,
            'username'=> 'john_smith',
            'name'=> 'John Smith',
            'state'=> 'active',
            'avatar_url'=> 'http=>//localhost=>3000/uploads/user/avatar/1/cd8.jpeg',
        ]);

        $this->assertEquals(1, $actual->id);
        $this->assertEquals('john_smith', $actual->username);
        $this->assertEquals('John Smith', $actual->name);
        $this->assertEquals('active', $actual->state);
        $this->assertEquals('http=>//localhost=>3000/uploads/user/avatar/1/cd8.jpeg', $actual->avatarUrl);
    }
}
