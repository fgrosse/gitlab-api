<?php

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
        $this->assertEquals('/projects/'.urlencode($projectId).'/repository/commits', $request->getPath());
        $this->assertEquals('application/json', $request->getHeader('Accept'));
        $this->assertRequestHasQueryParameter('ref_name', 'develop', $request);
        $this->assertRequestHasQueryParameter('page', 3, $request);
        $this->assertRequestHasQueryParameter('per_page', 15, $request);
    }

    public function testSingleCommit()
    {
        $this->markTestIncomplete('Not implemented yet');
    }

    public function testGetDiff()
    {
        $this->markTestIncomplete('Not implemented yet');
    }

    public function testGetComments()
    {
        $this->markTestIncomplete('Not implemented yet');
    }

    public function testCreateComment()
    {
        $this->markTestIncomplete('Not implemented yet');
    }
}
