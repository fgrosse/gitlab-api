<?php

use Gitlab\GuzzleClient;
use GuzzleHttp\Message\RequestInterface;
use GuzzleHttp\Subscriber\History;
use GuzzleHttp\Subscriber\Mock as ResponseMock;

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
        $this->assertEquals('application/json', $request->getHeader('Accept'));
        $this->assertEquals('/projects/'.urlencode($projectId).'/merge_requests', $request->getPath());
        $this->assertRequestHasQueryParameter('state', 'closed', $request);
        $this->assertRequestHasQueryParameter('order_by', 'updated_at', $request);
        $this->assertRequestHasQueryParameter('page', 3, $request);
        $this->assertRequestHasQueryParameter('per_page', 15, $request);
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
}
