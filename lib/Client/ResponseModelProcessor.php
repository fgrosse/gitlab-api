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

use GuzzleHttp\Command\Event\ProcessEvent;
use GuzzleHttp\Command\Guzzle\DescriptionInterface;
use GuzzleHttp\Event\SubscriberInterface;
use RuntimeException;

class ResponseModelProcessor implements SubscriberInterface
{
    /** @var DescriptionInterface */
    private $description;

    public function __construct(DescriptionInterface $description)
    {
        $this->description = $description;
    }

    public function getEvents()
    {
        return ['process' => ['onProcess', ]];
    }

    public function onProcess(ProcessEvent $event)
    {
        // Only add a result object if no exception was encountered.
        if ($event->getException()) {
            return;
        }

        $result = $event->getResult();
        if (is_array($result) == false) {
            return;
        }

        $command = $event->getCommand();
        $operation = $this->description->getOperation($command->getName());
        $rawConfig = $operation->toArray();
        if (empty($rawConfig['responseParser'])) {
            return;
        }

        $responseParser = $rawConfig['responseParser'];
        if (class_exists($responseParser) == false) {
            throw new RuntimeException("Unknown response parser: {$responseParser}");
        }

        if (in_array(ResponseParser::class, class_implements($responseParser)) == false) {
            throw new RuntimeException("Response parser {$responseParser} does not implement " . ResponseParser::class);
        }

        /** @var ResponseParser $responseParser */
        $parsedResponse = $responseParser::fromArray($result);
        $event->setResult($parsedResponse);
    }
}
