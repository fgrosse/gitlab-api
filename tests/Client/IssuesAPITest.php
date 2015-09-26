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

class IssuesAPITest extends GitlabClientTest
{
    public function testListIssues()
    {
        $this->setMockResponse(__DIR__ . '/fixtures/issues/list_issues.http');
        $this->client->listIssues([
            'state'      => 'closed',
            'labels'     => 'foo,bar',
            'order_by'   => 'updated_at',
            'sort'       => 'asc',
            'page'       => 3,
            'per_page'   => 15,
        ]);

        $request = $this->requestHistory->getLastRequest();
        $this->assertEquals('GET', $request->getMethod());
        $this->assertEquals('/gitlab/api/v3/issues', $request->getPath());
        $this->assertEquals('application/json', $request->getHeader('Accept'));
        $this->assertRequestHasQueryParameter('state', 'closed', $request);
        $this->assertRequestHasQueryParameter('labels', 'foo,bar', $request);
        $this->assertRequestHasQueryParameter('order_by', 'updated_at', $request);
        $this->assertRequestHasQueryParameter('sort', 'asc', $request);
        $this->assertRequestHasQueryParameter('page', 3, $request);
        $this->assertRequestHasQueryParameter('per_page', 15, $request);
    }

    public function testListProjectIssues()
    {
        $this->setMockResponse(__DIR__ . '/fixtures/issues/list_issues.http');
        $projectId = 'fgrosse/example-project';
        $this->client->listProjectIssues([
            'project_id' => $projectId,
            'state'      => 'closed',
            'labels'     => 'foo,bar',
            'milestone'  => '1.0.0',
            'order_by'   => 'updated_at',
            'sort'       => 'asc',
            'page'       => 3,
            'per_page'   => 15,
        ]);

        $request = $this->requestHistory->getLastRequest();
        $this->assertEquals('GET', $request->getMethod());
        $this->assertEquals('/gitlab/api/v3/projects/'.urlencode($projectId).'/issues', $request->getPath());
        $this->assertEquals('application/json', $request->getHeader('Accept'));
        $this->assertRequestHasQueryParameter('state', 'closed', $request);
        $this->assertRequestHasQueryParameter('labels', 'foo,bar', $request);
        $this->assertRequestHasQueryParameter('milestone', '1.0.0', $request);
        $this->assertRequestHasQueryParameter('order_by', 'updated_at', $request);
        $this->assertRequestHasQueryParameter('sort', 'asc', $request);
        $this->assertRequestHasQueryParameter('page', 3, $request);
        $this->assertRequestHasQueryParameter('per_page', 15, $request);
    }

    public function testGetIssue()
    {
        $this->setMockResponse(__DIR__ . '/fixtures/issues/single_issue.http');
        $projectId = 'fgrosse/example-project';
        $this->client->getIssue([
            'project_id' => $projectId,
            'issue_id'   => 15,
        ]);

        $request = $this->requestHistory->getLastRequest();
        $this->assertEquals('GET', $request->getMethod());
        $this->assertEquals('/gitlab/api/v3/projects/'.urlencode($projectId).'/issues/15', $request->getPath());
        $this->assertEquals('application/json', $request->getHeader('Accept'));
    }

    public function testCreateIssue()
    {
        $this->setMockResponse(__DIR__ . '/fixtures/issues/create_issue.http');
        $projectId = 'fgrosse/example-project';
        $this->client->createIssue([
            'project_id'   => $projectId,
            'title'        => 'This is the issue title',
            'description'  => 'This is the issue description',
            'assignee_id'  => 42,
            'milestone_id' => 24,
            'labels'       => 'feature',
        ]);

        $request = $this->requestHistory->getLastRequest();
        $this->assertEquals('POST', $request->getMethod());
        $this->assertEquals('/gitlab/api/v3/projects/'.urlencode($projectId).'/issues', $request->getPath());
        $this->assertEquals('application/json', $request->getHeader('Accept'));
        $this->assertRequestHasPostParameter('title', 'This is the issue title', $request);
        $this->assertRequestHasPostParameter('description', 'This is the issue description', $request);
        $this->assertRequestHasPostParameter('assignee_id', 42, $request);
        $this->assertRequestHasPostParameter('milestone_id', 24, $request);
        $this->assertRequestHasPostParameter('labels', 'feature', $request);
    }

    public function testUpdateIssue()
    {
        $this->setMockResponse(__DIR__ . '/fixtures/issues/update_issue.http');
        $projectId = 'fgrosse/example-project';
        $issueId = 123;
        $this->client->updateIssue([
            'project_id'   => $projectId,
            'issue_id'     => $issueId,
            'title'        => 'This is the issue title',
            'description'  => 'This is the issue description',
            'assignee_id'  => 42,
            'milestone_id' => 24,
            'labels'       => 'feature',
            'state_event'  => 'close',
        ]);

        $request = $this->requestHistory->getLastRequest();
        $this->assertEquals('PUT', $request->getMethod());
        $this->assertEquals('/gitlab/api/v3/projects/'.urlencode($projectId)."/issues/$issueId", $request->getPath());
        $this->assertEquals('application/json', $request->getHeader('Accept'));
        $this->assertRequestHasPostParameter('title', 'This is the issue title', $request);
        $this->assertRequestHasPostParameter('description', 'This is the issue description', $request);
        $this->assertRequestHasPostParameter('assignee_id', 42, $request);
        $this->assertRequestHasPostParameter('milestone_id', 24, $request);
        $this->assertRequestHasPostParameter('labels', 'feature', $request);
        $this->assertRequestHasPostParameter('state_event', 'close', $request);
    }
}
