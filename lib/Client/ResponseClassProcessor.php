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

class ResponseClassProcessor implements SubscriberInterface
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

        $command = $event->getCommand();
        $operation = $this->description->getOperation($command->getName());
        $rawConfig = $operation->toArray();
        if (empty($rawConfig['responseParser'])) {
            return;
        }

        $parsedResponse = $this->parseResponse($event, $rawConfig['responseParser']);
        $event->setResult($parsedResponse);
    }

    private function parseResponse(ProcessEvent $event, $responseParserClass)
    {
        if (class_exists($responseParserClass) == false) {
            throw new RuntimeException("Unknown response parser: {$responseParserClass}");
        }

        $result = $event->getResult();
        if (is_array($result) && in_array(ArrayParsable::class, class_implements($responseParserClass))) {
            /** @var ArrayParsable $responseParserClass */
            return $responseParserClass::fromArray($result);
        }

        throw new RuntimeException("Response parser {$responseParserClass} does not implement ".ArrayParsable::class);
    }
}
