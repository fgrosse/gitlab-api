<?php

namespace Gitlab\Test\Client;

use Gitlab\Client\GuzzleClient;
use Gitlab\Client\PrivateTokenPlugin;
use GuzzleHttp\Message\RequestInterface;
use GuzzleHttp\Post\PostBody;
use GuzzleHttp\Subscriber\History;
use GuzzleHttp\Subscriber\Mock as ResponseMock;
use PHPUnit_Framework_TestCase;

class GuzzleClientFactoryMethodTest extends PHPUnit_Framework_TestCase
{
    public function testFactoryAddsApiPathToBasePathIfNecessary()
    {
        $baseUrls = [
            'https://example.com/gitlab',
            'https://example.com/gitlab/',
            'https://example.com/gitlab/api/v3',
            'https://example.com/gitlab/api/v3/',
            'https://example.com:8080/gitlab/api/v3'
        ];
        foreach ($baseUrls as $baseUrl) {
            $client = GuzzleClient::factory([
                'base_url' => $baseUrl,
                'api_token' => 'QVy1PB7sTxfy4pqfZM1U',
            ]);
            $baseUrlPath = parse_url($client->getHttpClient()->getBaseUrl(), PHP_URL_PATH);
            if (substr($baseUrlPath, -1) === '/') {
                $baseUrlPath = substr($baseUrlPath, 0, strlen($baseUrlPath)-1);
            }
            $this->assertEquals("/gitlab/api/v3", $baseUrlPath);
        }
    }

    public function testFactoryAddsPrivateTokenPluginToHttpClient()
    {
        $client = GuzzleClient::factory([
            'base_url' => 'https://example.com/gitlab',
            'api_token' => 'QVy1PB7sTxfy4pqfZM1U',
        ]);
        $emitter = $client->getHttpClient()->getEmitter();
        $this->assertTrue($emitter->hasListeners('before'), 'The factory method should add the PrivateTokenPlugin listener automatically');

        $listeners = $emitter->listeners('before');
        foreach ($listeners as $listener) {
            $this->assertArrayHasKey(0, $listener);
            if ($listener[0] instanceof PrivateTokenPlugin) {
                return;
            }
        }

        $this->fail("Expected the guzzle client to have the " . PrivateTokenPlugin::class);
    }
}
