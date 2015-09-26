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

class CommitsAPITest extends GitlabClientTest
{
    public function testListCommits()
    {
        $this->setMockResponse(__DIR__ . '/fixtures/commits/list_commits.http');
        $projectId = 'fgrosse/example-project';
        $this->client->listCommits([
            'project_id' => $projectId,
            'ref_name'   => 'develop',
            'page'       => 3,
            'per_page'   => 15,
        ]);

        $request = $this->requestHistory->getLastRequest();
        $this->assertEquals('GET', $request->getMethod());
        $this->assertEquals('/gitlab/api/v3/projects/'.urlencode($projectId).'/repository/commits', $request->getPath());
        $this->assertEquals('application/json', $request->getHeader('Accept'));
        $this->assertRequestHasQueryParameter('ref_name', 'develop', $request);
        $this->assertRequestHasQueryParameter('page', 3, $request);
        $this->assertRequestHasQueryParameter('per_page', 15, $request);
    }

    public function testGetCommit()
    {
        $this->setMockResponse(__DIR__ . '/fixtures/commits/get_commit.http');
        $projectId = 'fgrosse/example-project';
        $commitHash = 'a0ec05a1aa822643a98d5b86aceabf29985ebc10';
        $this->client->getCommit([
            'project_id' => $projectId,
            'sha'        => $commitHash,
        ]);

        $request = $this->requestHistory->getLastRequest();
        $this->assertEquals('GET', $request->getMethod());
        $this->assertEquals('/gitlab/api/v3/projects/'.urlencode($projectId)."/repository/commits/{$commitHash}", $request->getPath());
        $this->assertEquals('application/json', $request->getHeader('Accept'));
    }

    public function testGetDiff()
    {
        $this->setMockResponse(__DIR__ . '/fixtures/commits/get_commit_diff.http');
        $projectId = 'fgrosse/example-project';
        $commitHash = 'a0ec05a1aa822643a98d5b86aceabf29985ebc10';
        $this->client->getCommitDiff([
            'project_id' => $projectId,
            'sha'        => $commitHash,
        ]);

        $request = $this->requestHistory->getLastRequest();
        $this->assertEquals('GET', $request->getMethod());
        $this->assertEquals('/gitlab/api/v3/projects/'.urlencode($projectId)."/repository/commits/{$commitHash}/diff", $request->getPath());
        $this->assertEquals('application/json', $request->getHeader('Accept'));
    }

    public function testGetComments()
    {
        $this->setMockResponse(__DIR__ . '/fixtures/commits/get_commit_comments.http');
        $projectId = 'fgrosse/example-project';
        $commitHash = 'a0ec05a1aa822643a98d5b86aceabf29985ebc10';
        $this->client->getCommitComments([
            'project_id' => $projectId,
            'sha'        => $commitHash,
        ]);

        $request = $this->requestHistory->getLastRequest();
        $this->assertEquals('GET', $request->getMethod());
        $this->assertEquals('/gitlab/api/v3/projects/'.urlencode($projectId)."/repository/commits/{$commitHash}/comments", $request->getPath());
        $this->assertEquals('application/json', $request->getHeader('Accept'));
    }

    public function testCreateComment()
    {
        $this->setMockResponse(__DIR__ . '/fixtures/commits/create_commit_comment.http');
        $projectId = 'fgrosse/example-project';
        $commitHash = 'a0ec05a1aa822643a98d5b86aceabf29985ebc10';
        $this->client->createCommitComment([
            'project_id' => $projectId,
            'sha'        => $commitHash,
            'note'       => 'Hello Commit Comment World!',
            'path'       => 'example.rb',
            'line'       => 5,
            'line_type'  => 'new',
        ]);

        $request = $this->requestHistory->getLastRequest();
        $this->assertEquals('POST', $request->getMethod());
        $this->assertEquals('/gitlab/api/v3/projects/'.urlencode($projectId)."/repository/commits/{$commitHash}/comments", $request->getPath());
        $this->assertEquals('application/json', $request->getHeader('Accept'));
        $this->assertRequestHasPostParameter('note', 'Hello Commit Comment World!', $request);
        $this->assertRequestHasPostParameter('path', 'example.rb', $request);
        $this->assertRequestHasPostParameter('line', 5, $request);
        $this->assertRequestHasPostParameter('line_type', 'new', $request);
    }
}
