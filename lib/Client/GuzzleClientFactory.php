<?php

namespace Gitlab\Client;

use Gitlab\Utils\String;
use GuzzleHttp\Client;
use GuzzleHttp\Collection;
use GuzzleHttp\Command\Guzzle\Description;
use Symfony\Component\Yaml\Yaml;

/**
 * The GuzzleClientFactory can created fully configured instances of a GitlabClient.
 * The client is configured using the service definitions from the API folder.
 */
class GuzzleClientFactory
{
    /**
     * Create a new instance of GitlabClient (automatically configured with the service.yml)
     * @param array $config
     * @return GitlabClient
     */
    public static function createClient($config = [])
    {
        $required = [ 'base_url', 'api_token' ];
        $config = Collection::fromConfig($config, $default = [], $required);
        $config['base_url'] = self::completeBaseUrl($config['base_url']);

        $description = self::loadServiceDefinition();

        $client = new Client($config->toArray());
        $client->setDefaultOption('headers/accept', 'application/json');

        $privateTokenPlugin = new PrivateTokenPlugin($config['api_token']);
        $client->getEmitter()->attach($privateTokenPlugin);

        return new GitlabClient($client, $description);
    }

    private static function completeBaseUrl($originalBaseUrl)
    {
        $baseUrl = $originalBaseUrl;
        if (String::endsWith($baseUrl, '/') == false) {
            $baseUrl .= '/';
        }

        $baseUrlPath = parse_url($baseUrl, PHP_URL_PATH);
        if (String::endsWith($baseUrlPath, '/api/v3/')) {
            return $originalBaseUrl;
        }

        return $baseUrl.'api/v3';
    }

    private static function loadServiceDefinition()
    {
        $descriptionArray = Yaml::parse(__DIR__ . '/ServiceDescription/service_description.yml');
        foreach($descriptionArray['imports'] as $apiDescriptionFile) {
            $apiDescription = Yaml::parse(__DIR__ . "/ServiceDescription/$apiDescriptionFile");
            $descriptionArray = array_merge_recursive($descriptionArray, $apiDescription);
        }
        unset($descriptionArray['imports']);
        return new Description($descriptionArray);
    }
}
