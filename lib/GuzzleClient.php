<?php

namespace Gitlab;

use GuzzleHttp\Client;
use GuzzleHttp\Collection;
use GuzzleHttp\Command\Guzzle\Description;
use Symfony\Component\Yaml\Yaml;

/**
 * Class GuzzleClient is the underlying guzzle client which is configured completely via
 * the service definition (service.yml)
 *
 * The following magic methods are available through the service definition:
 * @method listMergeRequests
 */
class GuzzleClient extends \GuzzleHttp\Command\Guzzle\GuzzleClient
{
    /**
     * Create a new instance of GuzzleClient (automatically configured with the service.yml)
     * @param array $config
     * @return GuzzleClient
     */
    public static function factory($config = [])
    {
        $config = Collection::fromConfig($config, $default = [], $required = ['base_url']);

        $descriptionArray = Yaml::parse(__DIR__ . '/service.yml');
        $description = new Description($descriptionArray);

        $client = new Client($config->toArray());
        $client->setDefaultOption('headers/accept', 'application/json');

        return new self($client, $description);
    }
}
