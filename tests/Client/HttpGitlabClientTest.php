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

use Gitlab\Client\GitlabGuzzleClient;
use Gitlab\Client\HttpGitlabClient;
use Gitlab\Entity\MergeRequest;
use Mockery;
use Mockery\MockInterface;
use PHPUnit_Framework_TestCase;

class HttpGitlabClientTest extends PHPUnit_Framework_TestCase
{
    /** @var GitlabGuzzleClient|MockInterface */
    private $guzzle;

    /** @var HttpGitlabClient */
    private $client;

    protected function setUp()
    {
        $this->guzzle = Mockery::mock(GitlabGuzzleClient::class);
        $this->client = new HttpGitlabClient($this->guzzle);
    }

    public function testListMergeRequests()
    {
        $expectedResult = [
            new MergeRequest('fgrosse/example', 42, 'MR No. 42'),
            new MergeRequest('fgrosse/example', 43, "cthulhu r'lyeh fhtagn"),
            new MergeRequest('fgrosse/example', 44, 'Hello Gitlab World'),
        ];

        $this->guzzle->shouldReceive('listMergeRequests')->once()->with(Mockery::on(function ($params) {
            $this->assertEquals([
                'project_id' => 'fgrosse/example',
                'state'      => 'opened',
                'order_by'   => 'created_at',
                'sort'       => 'desc',
                'page'       => 42,
                'per_page'   => 3,
            ], $params);
            return true;
        }))->andReturn($expectedResult);

        $actualResult = $this->client->listMergeRequests('fgrosse/example', 'opened', 'created_at', 'desc', 42, 3);
        $this->assertEquals($expectedResult, $actualResult);
    }
}
