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

class MergeRequestTest extends PHPUnit_Framework_TestCase
{
    public function testFromArray()
    {
        $actual = MergeRequest::fromArray([
            'id'=> 1,
            'iid'=> 2,
            'target_branch'=> 'master',
            'source_branch'=> 'test1',
            'project_id'=> 3,
            'title'=> 'test1',
            'state'=> 'opened',
            'upvotes'=> 4,
            'downvotes'=> 5,
            'author'=> [
                'id'=> 1,
                'username'=> 'admin',
                'email'=> 'admin@example.com',
                'name'=> 'Administrator',
                'state'=> 'active',
                'created_at'=> '2012-04-29T08=>46=>00Z'
            ],
            'assignee'=> [
                'id'=> 1,
                'username'=> 'admin',
                'email'=> 'admin@example.com',
                'name'=> 'Administrator',
                'state'=> 'active',
                'created_at'=> '2012-04-29T08=>46=>00Z'
            ],
            'description'=> 'fixed login page css paddings'
        ]);

        $this->assertEquals(1, $actual->id);
        $this->assertEquals(2, $actual->iid);
        $this->assertEquals('master', $actual->targetBranch);
        $this->assertEquals('test1', $actual->sourceBranch);
        $this->assertEquals(3, $actual->projectId);
        $this->assertEquals('test1', $actual->title);
        $this->assertEquals('opened', $actual->state);
        $this->assertEquals(4, $actual->upVotes);
        $this->assertEquals(5, $actual->downVotes);
        $this->assertEquals('fixed login page css paddings', $actual->description);
        $this->assertInstanceOf(User::class, $actual->author);
        $this->assertInstanceOf(User::class, $actual->assignee);
    }
}
