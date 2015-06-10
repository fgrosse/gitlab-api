<?php

namespace Client;

use Gitlab\Client\GitlabClient;
use GuzzleHttp\Subscriber\Mock;
use PHPUnit_Framework_TestCase;

class GuzzleMockingTest extends PHPUnit_Framework_TestCase
{
    public function testGuzzleServicesIssue70()
    {
        $this->markTestSkipped('Skipped until https://github.com/guzzle/guzzle-services/issues/70 is resolved');
        $client = GitlabClient::factory([
            'base_url' => 'https://example.com/gitlab/api/v3',
            'api_token' => 'QVy1PB7sTxfy4pqfZM1U',
        ]);

        $fixturePath = __DIR__ . '/fixtures/issues/list_issues.http';
        $mock = new Mock([$fixturePath]);
        $client->getHttpClient()->getEmitter()->attach($mock);

        $result = $client->listIssues([]);
        $this->assertNotEmpty($result);
    }
}
