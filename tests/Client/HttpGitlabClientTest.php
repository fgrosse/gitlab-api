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
use Gitlab\Client\GitlabGuzzleClient;
use Gitlab\Client\HttpGitlabClient;
use Gitlab\Entity\MergeRequest;
use Gitlab\Entity\User;
use Mockery;
use Mockery\MockInterface;
use PHPUnit_Framework_TestCase;

class HttpGitlabClientTest extends PHPUnit_Framework_TestCase
{
    /** @var GitlabGuzzleClient|MockInterface */
    private $guzzle;

    /** @var GitlabClient */
    private $client;

    protected function setUp()
    {
        $this->guzzle = Mockery::mock(GitlabGuzzleClient::class);
        $this->client = new HttpGitlabClient($this->guzzle);
    }

    public function testListMergeRequests()
    {
        $expectedResult = [
            new MergeRequest('fgrosse/example', 'MR No. 42'),
            new MergeRequest('fgrosse/example', "cthulhu r'lyeh fhtagn"),
            new MergeRequest('fgrosse/example', 'Hello Gitlab World'),
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

    public function testGetMergeRequest()
    {
        $expectedResult = new MergeRequest('fgrosse/example', 'MR No. 42', 42);

        $this->guzzle->shouldReceive('getMergeRequest')->once()->with(Mockery::on(function ($params) {
            $this->assertEquals([
                'project_id'       => 'fgrosse/example',
                'merge_request_id' => 42,
            ], $params);
            return true;
        }))->andReturn($expectedResult);

        $actualResult = $this->client->getMergeRequest('fgrosse/example', 42);
        $this->assertEquals($expectedResult, $actualResult);
    }

    public function testCreateMergeRequest()
    {
        $assignee = new User();
        $assignee->id = 1;
        $mergeRequest = new MergeRequest('fgrosse/example', 'MR No. 42');
        $mergeRequest->sourceBranch = 'develop';
        $mergeRequest->targetBranch = 'master';
        $mergeRequest->assignee = $assignee;
        $mergeRequest->description = 'My description';
        $expectedResult = clone $mergeRequest;
        $expectedResult->id = 42;

        $this->guzzle->shouldReceive('createMergeRequest')->once()->with(Mockery::on(function ($params) {
            $this->assertEquals([
                'project_id' => 'fgrosse/example',
                'source_branch' => 'develop',
                'target_branch' => 'master',
                'title' => 'MR No. 42',
                'assignee_id' => 1,
                'description' => 'My description',
            ], $params);
            return true;
        }))->andReturn($expectedResult);

        $actualResult = $this->client->createMergeRequest($mergeRequest);
        $this->assertEquals($expectedResult, $actualResult);
    }

    public function testUpdateMergeRequest()
    {
        $assignee = new User();
        $assignee->id = 1;
        $mergeRequest = new MergeRequest('fgrosse/example', 'MR No. 42', 42);
        $mergeRequest->sourceBranch = 'develop';
        $mergeRequest->targetBranch = 'master';
        $mergeRequest->assignee = $assignee;
        $mergeRequest->description = 'My description';
        $expectedResult = clone $mergeRequest;

        $this->guzzle->shouldReceive('updateMergeRequest')->once()->with(Mockery::on(function ($params) {
            $this->assertEquals([
                'project_id' => 'fgrosse/example',
                'merge_request_id' => 42,
                'source_branch' => 'develop',
                'target_branch' => 'master',
                'title' => 'MR No. 42',
                'assignee_id' => 1,
                'description' => 'My description',
            ], $params);
            return true;
        }))->andReturn($expectedResult);

        $actualResult = $this->client->updateMergeRequest($mergeRequest);
        $this->assertEquals($expectedResult, $actualResult);
    }
}
