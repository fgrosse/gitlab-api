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

use Gitlab\Client\PrivateTokenPlugin;
use GuzzleHttp\Event\BeforeEvent;
use GuzzleHttp\Message\Request;
use Mockery;
use PHPUnit_Framework_TestCase;

class PrivateTokenPluginTest extends PHPUnit_Framework_TestCase
{
    public function testOnBefore()
    {
        $token = 'QVy1PB7sTxfy4pqfZM1U';
        $plugin = new PrivateTokenPlugin($token);

        $request = Mockery::mock(Request::class);
        $event = Mockery::mock(BeforeEvent::class);
        $event->shouldReceive('getRequest')->andReturn($request);
        $request->shouldReceive('addHeader')
                ->with(PrivateTokenPlugin::HEADER_NAME, $token)
                ->once();

        /** @var BeforeEvent $event */
        $plugin->onBefore($event, 'name');
    }
}
