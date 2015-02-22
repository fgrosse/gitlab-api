<?php

namespace Gitlab\Test\Client;

use Gitlab\Client\GuzzleClient;
use GuzzleHttp\Message\RequestInterface;
use GuzzleHttp\Post\PostBody;
use GuzzleHttp\Subscriber\History;
use GuzzleHttp\Subscriber\Mock as ResponseMock;
use PHPUnit_Framework_TestCase;

class GuzzleClientTest extends PHPUnit_Framework_TestCase
{
    /** @var GuzzleClient */
    private $client;

    /** @var History */
    private $requestHistory;

    protected function setUp()
    {
        $this->client = GuzzleClient::factory([
            'base_url' => 'https://example.com/gitlab',
        ]);

        $this->requestHistory = new History();
        $this->client->getHttpClient()->getEmitter()->attach($this->requestHistory);
    }

    public function testListMergeRequests()
    {
        $this->setMockResponse(__DIR__ . '/fixtures/list_merge_requests.http');
        $projectId = 'fgrosse/example-project';
        $this->client->listMergeRequests([
            'project_id' => $projectId,
            'state'      => 'closed',
            'order_by'   => 'updated_at',
            'sort'       => 'asc',
            'page'       => 3,
            'per_page'   => 15,
        ]);

        $request = $this->requestHistory->getLastRequest();
        $this->assertEquals('GET', $request->getMethod());
        $this->assertEquals('/projects/'.urlencode($projectId).'/merge_requests', $request->getPath());
        $this->assertEquals('application/json', $request->getHeader('Accept'));
        $this->assertRequestHasQueryParameter('state', 'closed', $request);
        $this->assertRequestHasQueryParameter('order_by', 'updated_at', $request);
        $this->assertRequestHasQueryParameter('page', 3, $request);
        $this->assertRequestHasQueryParameter('per_page', 15, $request);
    }

    public function testSingleMergeRequests()
    {
        $this->setMockResponse(__DIR__ . '/fixtures/single_merge_request.http');
        $projectId = 'fgrosse/example-project';
        $this->client->singleMergeRequest([
            'project_id' => $projectId,
            'merge_request_id' => 42,
        ]);

        $request = $this->requestHistory->getLastRequest();
        $this->assertEquals('GET', $request->getMethod());
        $this->assertEquals('/projects/'.urlencode($projectId).'/merge_requests/42', $request->getPath());
        $this->assertEquals('application/json', $request->getHeader('Accept'));
    }

    public function testSingleMergeRequestsChanges()
    {
        $this->setMockResponse(__DIR__ . '/fixtures/single_merge_request_changes.http');
        $projectId = 'fgrosse/example-project';
        $this->client->singleMergeRequestChanges([
            'project_id' => $projectId,
            'merge_request_id' => 42,
        ]);

        $request = $this->requestHistory->getLastRequest();
        $this->assertEquals('GET', $request->getMethod());
        $this->assertEquals('/projects/'.urlencode($projectId).'/merge_requests/42/changes', $request->getPath());
        $this->assertEquals('application/json', $request->getHeader('Accept'));
    }

    public function testCreateMergeRequest()
    {
        $this->setMockResponse(__DIR__ . '/fixtures/create_merge_request.http');
        $projectId = 'fgrosse/example-project';
        $this->client->createMergeRequest([
            'project_id'    => $projectId,
            'source_branch' => 'feature/test',
            'target_branch' => 'develop',
            'assignee_id'   => 42,
            'title'         => 'Test MR',
            'description'   => 'This is a description',
            'target_project_id' => 123,
        ]);

        $request = $this->requestHistory->getLastRequest();
        $this->assertEquals('POST', $request->getMethod());
        $this->assertEquals('/projects/'.urlencode($projectId).'/merge_requests', $request->getPath());
        $this->assertEquals('application/json', $request->getHeader('Accept'));
        $this->assertRequestHasPostParameter('source_branch', 'feature/test', $request);
        $this->assertRequestHasPostParameter('target_branch', 'develop', $request);
        $this->assertRequestHasPostParameter('assignee_id', 42, $request);
        $this->assertRequestHasPostParameter('title', 'Test MR', $request);
        $this->assertRequestHasPostParameter('description', 'This is a description', $request);
        $this->assertRequestHasPostParameter('target_project_id', 123, $request);
    }

    public function testUpdateMergeRequest()
    {
        $this->setMockResponse(__DIR__ . '/fixtures/update_merge_request.http');
        $projectId = 'fgrosse/example-project';
        $mergeRequestId = 42;
        $this->client->updateMergeRequest([
            'project_id'    => $projectId,
            'merge_request_id' => $mergeRequestId,
            'source_branch' => 'feature/test',
            'target_branch' => 'develop',
            'assignee_id'   => 42,
            'title'         => 'Test MR',
            'description'   => 'This is a description',
            'state_event'   => 'reopen',
        ]);

        $request = $this->requestHistory->getLastRequest();
        $this->assertEquals('PUT', $request->getMethod());
        $this->assertEquals('/projects/'.urlencode($projectId)."/merge_requests/$mergeRequestId", $request->getPath());
        $this->assertEquals('application/json', $request->getHeader('Accept'));
        $this->assertRequestHasPostParameter('source_branch', 'feature/test', $request);
        $this->assertRequestHasPostParameter('target_branch', 'develop', $request);
        $this->assertRequestHasPostParameter('assignee_id', 42, $request);
        $this->assertRequestHasPostParameter('title', 'Test MR', $request);
        $this->assertRequestHasPostParameter('description', 'This is a description', $request);
        $this->assertRequestHasPostParameter('state_event', 'reopen', $request);
    }

    public function testAcceptMergeRequest()
    {
        $this->setMockResponse(__DIR__ . '/fixtures/accept_merge_request.http');
        $projectId = 'fgrosse/example-project';
        $mergeRequestId = 42;
        $this->client->acceptMergeRequest([
            'project_id'    => $projectId,
            'merge_request_id' => $mergeRequestId,
            'merge_commit_message' => 'Merging foo into bar',
        ]);

        $request = $this->requestHistory->getLastRequest();
        $this->assertEquals('PUT', $request->getMethod());
        $this->assertEquals('/projects/'.urlencode($projectId)."/merge_requests/$mergeRequestId/merge", $request->getPath());
        $this->assertEquals('application/json', $request->getHeader('Accept'));
        $this->assertRequestHasPostParameter('merge_commit_message', 'Merging foo into bar', $request);
    }

    public function testCreateMergeRequestComment()
    {
        $this->setMockResponse(__DIR__ . '/fixtures/create_merge_request_comment.http');
        $projectId = 'fgrosse/example-project';
        $mergeRequestId = 42;
        $this->client->createMergeRequestComment([
            'project_id'       => $projectId,
            'merge_request_id' => $mergeRequestId,
            'note'             => 'This is a comment',
        ]);

        $request = $this->requestHistory->getLastRequest();
        $this->assertEquals('POST', $request->getMethod());
        $this->assertEquals('/projects/'.urlencode($projectId)."/merge_requests/$mergeRequestId/comments", $request->getPath());
        $this->assertEquals('application/json', $request->getHeader('Accept'));
        $this->assertRequestHasPostParameter('note', 'This is a comment', $request);
    }

    public function testListMergeRequestComments()
    {
        $this->setMockResponse(__DIR__ . '/fixtures/list_merge_request_comments.http');
        $projectId = 'fgrosse/example-project';
        $mergeRequestId = 42;
        $this->client->listMergeRequestComments([
            'project_id'       => $projectId,
            'merge_request_id' => $mergeRequestId,
        ]);

        $request = $this->requestHistory->getLastRequest();
        $this->assertEquals('GET', $request->getMethod());
        $this->assertEquals('/projects/'.urlencode($projectId)."/merge_requests/$mergeRequestId/comments", $request->getPath());
    }

    private function setMockResponse($path)
    {
        $mock = new ResponseMock([$path]);
        $this->client->getHttpClient()->getEmitter()->attach($mock);
    }

    private function assertRequestHasQueryParameter($parameterName, $expectedValue, RequestInterface $request)
    {
        $actualValue = $request->getQuery()->get($parameterName);
        $this->assertNotNull($actualValue, "The request should have the query parameter '$parameterName'");
        $this->assertEquals($expectedValue, $actualValue);
    }

    private function assertRequestHasPostParameter($parameterName, $expectedValue, RequestInterface $request)
    {
        /** @var PostBody $requestBody */
        $requestBody = $request->getBody();
        $this->assertNotEmpty($requestBody);
        $this->assertInstanceOf(PostBody::class, $requestBody);
        $actualValue = $requestBody->getField($parameterName);
        $this->assertNotNull($actualValue, "The request should have the post body parameter '$parameterName'");
        $this->assertEquals($expectedValue, $actualValue);
    }
}
