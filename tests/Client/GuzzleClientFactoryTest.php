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

use Gitlab\Client\GuzzleClientFactory;
use Gitlab\Client\PrivateTokenPlugin;
use PHPUnit_Framework_TestCase;

class GuzzleClientFactoryTest extends PHPUnit_Framework_TestCase
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
            $client = GuzzleClientFactory::createClient([
                'base_url' => $baseUrl,
                'api_token' => 'QVy1PB7sTxfy4pqfZM1U',
            ]);
            $baseUrlPath = parse_url($client->getHttpClient()->getBaseUrl(), PHP_URL_PATH);
            $this->assertEquals("/gitlab/api/v3/", $baseUrlPath);
        }
    }

    public function testFactoryAddsPrivateTokenPluginToHttpClient()
    {
        $client = GuzzleClientFactory::createClient([
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
