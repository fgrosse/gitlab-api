<?php
/*
 * This file is part of fgrosse/gitlab-api.
 *
 * Copyright © Friedrich Große <friedrich.grosse@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Client;

use Gitlab\Client\GitlabClient;
use Gitlab\Client\MockGitlabClient;
use PHPUnit_Framework_TestCase;

class GitlabMockClientTest extends PHPUnit_Framework_TestCase
{
    public function testMockClientIsAGitlabClient()
    {
        $this->assertInstanceOf(GitlabClient::class, new MockGitlabClient());
    }
}
