<?php

namespace Gitlab\Client;

use GuzzleHttp\Client;
use GuzzleHttp\Collection;
use GuzzleHttp\Command\Guzzle\Description;
use Symfony\Component\Yaml\Yaml;

/**
 * Class GuzzleClient is the underlying guzzle client which is configured completely via
 * the service definition (service.yml)
 *
 * The following magic methods are available through the service definition:
 *
 * Merge Requests API:
 * @method listMergeRequests
 * @method singleMergeRequest
 * @method singleMergeRequestChanges
 * @method createMergeRequest
 *
 * TODO: branches API
 * TODO: commits API
 * TODO: deploy_key_multiple_projects API
 * TODO: deploy_keys API
 * TODO: groups API
 * TODO: issues API
 * TODO: labels API
 * TODO: milestones API
 * TODO: notes API
 * TODO: oauth2 API
 * TODO: project_snippets API
 * TODO: projects API
 * TODO: repositories API
 * TODO: repository_files API
 * TODO: services API
 * TODO: session API
 * TODO: system_hooks API
 * TODO: users API
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
