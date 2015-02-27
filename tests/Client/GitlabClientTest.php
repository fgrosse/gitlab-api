<?php

namespace Gitlab\Test\Client;

use Gitlab\Client\GitlabClient;
use GuzzleHttp\Message\RequestInterface;
use GuzzleHttp\Post\PostBody;
use GuzzleHttp\Subscriber\History;
use GuzzleHttp\Subscriber\Mock as ResponseMock;
use PHPUnit_Framework_TestCase;

abstract class GitlabClientTest extends PHPUnit_Framework_TestCase
{
    /** @var GitlabClient */
    protected $client;

    /** @var History */
    protected $requestHistory;

    protected function setUp()
    {
        $this->client = GitlabClient::factory([
            'base_url' => 'https://example.com/gitlab/api/v3',
            'api_token' => 'QVy1PB7sTxfy4pqfZM1U',
        ]);

        $this->requestHistory = new History();
        $this->client->getHttpClient()->getEmitter()->attach($this->requestHistory);
    }

    protected function setMockResponse($path)
    {
        $mock = new ResponseMock([$path]);
        $this->client->getHttpClient()->getEmitter()->attach($mock);
    }

    protected function assertRequestHasQueryParameter($parameterName, $expectedValue, RequestInterface $request)
    {
        $actualValue = $request->getQuery()->get($parameterName);
        $this->assertNotNull($actualValue, "The request should have the query parameter '$parameterName'");
        $this->assertEquals($expectedValue, $actualValue);
    }

    protected function assertRequestHasPostParameter($parameterName, $expectedValue, RequestInterface $request)
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
