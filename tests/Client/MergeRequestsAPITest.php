<?php
/*
 * This file is part of fgrosse/gitlab-api.
 *
 * Copyright © Friedrich Große <friedrich.grosse@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gitlab\Test\Client;

use Gitlab\Entity\Comment;
use Gitlab\Entity\MergeRequest;

class MergeRequestsAPITest extends GitlabGuzzleClientTest
{
    public function testListMergeRequests()
    {
        $this->setMockResponse(__DIR__.'/fixtures/merge_requests/list_merge_requests.http');
        $projectId = 'fgrosse/example-project';
        $mergeRequests = $this->client->listMergeRequests([
            'project_id' => $projectId,
            'state'      => 'closed',
            'order_by'   => 'updated_at',
            'sort'       => 'asc',
            'page'       => 3,
            'per_page'   => 15,
        ]);

        $request = $this->requestHistory->getLastRequest();
        $this->assertEquals('GET', $request->getMethod());
        $this->assertEquals('/gitlab/api/v3/projects/'.urlencode($projectId).'/merge_requests', $request->getPath());
        $this->assertEquals('application/json', $request->getHeader('Accept'));
        $this->assertRequestHasQueryParameter('state', 'closed', $request);
        $this->assertRequestHasQueryParameter('order_by', 'updated_at', $request);
        $this->assertRequestHasQueryParameter('sort', 'asc', $request);
        $this->assertRequestHasQueryParameter('page', 3, $request);
        $this->assertRequestHasQueryParameter('per_page', 15, $request);

        $this->assertContainsOnlyInstancesOf(MergeRequest::class, $mergeRequests);
    }

    public function testGetMergeRequest()
    {
        $this->setMockResponse(__DIR__.'/fixtures/merge_requests/single_merge_request.http');
        $projectId = 'fgrosse/example-project';
        $mergeRequest = $this->client->getMergeRequest([
            'project_id' => $projectId,
            'merge_request_id' => 42,
        ]);

        $request = $this->requestHistory->getLastRequest();
        $this->assertEquals('GET', $request->getMethod());
        $this->assertEquals('/gitlab/api/v3/projects/'.urlencode($projectId).'/merge_requests/42', $request->getPath());
        $this->assertEquals('application/json', $request->getHeader('Accept'));

        $this->assertInstanceOf(MergeRequest::class, $mergeRequest);
    }

    public function testGetMergeRequestsChanges()
    {
        $this->setMockResponse(__DIR__.'/fixtures/merge_requests/single_merge_request_changes.http');
        $projectId = 'fgrosse/example-project';
        $this->client->getMergeRequestChanges([
            'project_id' => $projectId,
            'merge_request_id' => 42,
        ]);

        $request = $this->requestHistory->getLastRequest();
        $this->assertEquals('GET', $request->getMethod());
        $this->assertEquals('/gitlab/api/v3/projects/'.urlencode($projectId).'/merge_requests/42/changes', $request->getPath());
        $this->assertEquals('application/json', $request->getHeader('Accept'));
    }

    public function testCreateMergeRequest()
    {
        $this->setMockResponse(__DIR__.'/fixtures/merge_requests/create_merge_request.http');
        $projectId = 'fgrosse/example-project';
        $mergeRequest = $this->client->createMergeRequest([
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
        $this->assertEquals('/gitlab/api/v3/projects/'.urlencode($projectId).'/merge_requests', $request->getPath());
        $this->assertEquals('application/json', $request->getHeader('Accept'));
        $this->assertRequestHasPostParameter('source_branch', 'feature/test', $request);
        $this->assertRequestHasPostParameter('target_branch', 'develop', $request);
        $this->assertRequestHasPostParameter('assignee_id', 42, $request);
        $this->assertRequestHasPostParameter('title', 'Test MR', $request);
        $this->assertRequestHasPostParameter('description', 'This is a description', $request);
        $this->assertRequestHasPostParameter('target_project_id', 123, $request);

        $this->assertInstanceOf(MergeRequest::class, $mergeRequest);
    }

    public function testUpdateMergeRequest()
    {
        $this->setMockResponse(__DIR__.'/fixtures/merge_requests/update_merge_request.http');
        $projectId = 'fgrosse/example-project';
        $mergeRequestId = 42;
        $mergeRequest = $this->client->updateMergeRequest([
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
        $this->assertEquals('/gitlab/api/v3/projects/'.urlencode($projectId)."/merge_requests/$mergeRequestId", $request->getPath());
        $this->assertEquals('application/json', $request->getHeader('Accept'));
        $this->assertRequestHasPostParameter('source_branch', 'feature/test', $request);
        $this->assertRequestHasPostParameter('target_branch', 'develop', $request);
        $this->assertRequestHasPostParameter('assignee_id', 42, $request);
        $this->assertRequestHasPostParameter('title', 'Test MR', $request);
        $this->assertRequestHasPostParameter('description', 'This is a description', $request);
        $this->assertRequestHasPostParameter('state_event', 'reopen', $request);

        $this->assertInstanceOf(MergeRequest::class, $mergeRequest);
    }

    public function testAcceptMergeRequest()
    {
        $this->setMockResponse(__DIR__.'/fixtures/merge_requests/accept_merge_request.http');
        $projectId = 'fgrosse/example-project';
        $mergeRequestId = 42;
        $mergeRequest = $this->client->acceptMergeRequest([
            'project_id'    => $projectId,
            'merge_request_id' => $mergeRequestId,
            'merge_commit_message' => 'Merging foo into bar',
        ]);

        $request = $this->requestHistory->getLastRequest();
        $this->assertEquals('PUT', $request->getMethod());
        $this->assertEquals('/gitlab/api/v3/projects/'.urlencode($projectId)."/merge_requests/$mergeRequestId/merge", $request->getPath());
        $this->assertEquals('application/json', $request->getHeader('Accept'));
        $this->assertRequestHasPostParameter('merge_commit_message', 'Merging foo into bar', $request);

        $this->assertInstanceOf(MergeRequest::class, $mergeRequest);
    }

    public function testCreateMergeRequestComment()
    {
        $this->setMockResponse(__DIR__.'/fixtures/merge_requests/create_merge_request_comment.http');
        $projectId = 'fgrosse/example-project';
        $mergeRequestId = 42;
        $comment = $this->client->createMergeRequestComment([
            'project_id'       => $projectId,
            'merge_request_id' => $mergeRequestId,
            'note'             => 'This is a comment',
        ]);

        $request = $this->requestHistory->getLastRequest();
        $this->assertEquals('POST', $request->getMethod());
        $this->assertEquals('/gitlab/api/v3/projects/'.urlencode($projectId)."/merge_requests/$mergeRequestId/comments", $request->getPath());
        $this->assertEquals('application/json', $request->getHeader('Accept'));
        $this->assertRequestHasPostParameter('note', 'This is a comment', $request);

        $this->assertInstanceOf(Comment::class, $comment);
    }

    public function testListMergeRequestComments()
    {
        $this->setMockResponse(__DIR__.'/fixtures/merge_requests/list_merge_request_comments.http');
        $projectId = 'fgrosse/example-project';
        $mergeRequestId = 42;
        $comments = $this->client->listMergeRequestComments([
            'project_id'       => $projectId,
            'merge_request_id' => $mergeRequestId,
            'page'             => 3,
            'per_page'         => 15,
        ]);

        $request = $this->requestHistory->getLastRequest();
        $this->assertEquals('GET', $request->getMethod());
        $this->assertEquals('/gitlab/api/v3/projects/'.urlencode($projectId)."/merge_requests/$mergeRequestId/comments", $request->getPath());
        $this->assertRequestHasQueryParameter('page', 3, $request);
        $this->assertRequestHasQueryParameter('per_page', 15, $request);

        $this->assertContainsOnlyInstancesOf(Comment::class, $comments);
    }
}
