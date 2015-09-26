<?php
/*
 * This file is part of fgrosse/gitlab-api.
 *
 * Copyright © Friedrich Große <friedrich.grosse@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gitlab\Client;

use GuzzleHttp\Event\BeforeEvent;
use GuzzleHttp\Event\SubscriberInterface;

class PrivateTokenPlugin implements SubscriberInterface
{
    const HEADER_NAME = 'PRIVATE-TOKEN';

    private $privateToken;

    /**
     * Create a new instance of PrivateTokenPlugin
     * @param string $privateToken the private token that is used to authenticate the requests to gitlab
     */
    public function __construct($privateToken)
    {
        $this->privateToken = $privateToken;
    }

    public function getEvents()
    {
        return [
            'before' => [ 'onBefore' ],
        ];
    }

    public function onBefore(BeforeEvent $event, $name)
    {
        $request = $event->getRequest();
        $request->addHeader(self::HEADER_NAME, $this->privateToken);
    }
}
