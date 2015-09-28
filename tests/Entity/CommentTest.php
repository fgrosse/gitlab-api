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

use Gitlab\Entity\Comment;
use Gitlab\Entity\User;
use PHPUnit_Framework_TestCase;

class CommentTest extends PHPUnit_Framework_TestCase
{
    public function testFromArray()
    {
        $actual = Comment::fromArray([
            'author' => [
                'id' => 1,
                'username' => 'admin',
                'email' => 'admin@example.com',
                'name' => 'Administrator',
                'blocked' => false,
                'created_at' => '2012-04-29T08=>46=>00Z',
            ],
            'note' => 'text1',
        ]);

        $this->assertEquals('text1', $actual->note);
        $this->assertInstanceOf(User::class, $actual->author);
    }
}
