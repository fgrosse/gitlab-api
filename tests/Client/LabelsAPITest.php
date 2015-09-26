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

class LabelsAPITest extends GitlabClientTest
{
    public function testListLabels()
    {
        $this->setMockResponse(__DIR__.'/fixtures/labels/list_labels.http');
        $projectId = 'fgrosse/example-project';
        $this->client->listLabels([
            'project_id' => $projectId,
        ]);

        $request = $this->requestHistory->getLastRequest();
        $this->assertEquals('GET', $request->getMethod());
        $this->assertEquals('/gitlab/api/v3/projects/'.urlencode($projectId).'/labels', $request->getPath());
        $this->assertEquals('application/json', $request->getHeader('Accept'));
    }

    public function testCreateNewLabel()
    {
        $this->setMockResponse(__DIR__.'/fixtures/labels/create_update_or_delete_label.http');
        $projectId = 'fgrosse/example-project';
        $this->client->createLabel([
            'project_id' => $projectId,
            'name'       => 'pink',
            'color'      => '#FFAABB',
        ]);

        $request = $this->requestHistory->getLastRequest();
        $this->assertEquals('POST', $request->getMethod());
        $this->assertEquals('/gitlab/api/v3/projects/'.urlencode($projectId).'/labels', $request->getPath());
        $this->assertEquals('application/json', $request->getHeader('Accept'));
        $this->assertRequestHasPostParameter('name', 'pink', $request);
        $this->assertRequestHasPostParameter('color', '#FFAABB', $request);
    }

    public function testUpdateLabel()
    {
        $this->setMockResponse(__DIR__.'/fixtures/labels/create_update_or_delete_label.http');
        $projectId = 'fgrosse/example-project';
        $this->client->updateLabel([
            'project_id' => $projectId,
            'name'       => 'pink',
            'new_name'   => 'awesome pink',
            'color'      => '#FFAABB',
        ]);

        $request = $this->requestHistory->getLastRequest();
        $this->assertEquals('PUT', $request->getMethod());
        $this->assertEquals('/gitlab/api/v3/projects/'.urlencode($projectId).'/labels', $request->getPath());
        $this->assertEquals('application/json', $request->getHeader('Accept'));
        $this->assertRequestHasPostParameter('name', 'pink', $request);
        $this->assertRequestHasPostParameter('new_name', 'awesome pink', $request);
        $this->assertRequestHasPostParameter('color', '#FFAABB', $request);
    }

    public function testDeleteLabel()
    {
        $this->setMockResponse(__DIR__.'/fixtures/labels/create_update_or_delete_label.http');
        $projectId = 'fgrosse/example-project';
        $this->client->deleteLabel([
            'project_id' => $projectId,
            'name'       => 'pink',
        ]);

        $request = $this->requestHistory->getLastRequest();
        $this->assertEquals('DELETE', $request->getMethod());
        $this->assertEquals('/gitlab/api/v3/projects/'.urlencode($projectId).'/labels', $request->getPath());
        $this->assertRequestHasPostParameter('name', 'pink', $request);
    }
}
