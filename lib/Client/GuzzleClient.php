<?php

namespace Gitlab\Client;

use Gitlab\Utils\String;
use GuzzleHttp\Client;
use GuzzleHttp\Collection;
use GuzzleHttp\Command\Command;
use GuzzleHttp\Command\Guzzle\Description;
use GuzzleHttp\Exception\RequestException;
use Symfony\Component\Yaml\Yaml;

/**
 * Class GuzzleClient is the underlying guzzle client which is configured completely via
 * the service definition (service.yml)
 *
 * The following magic methods are available through the service definition:
 *
 * Merge Requests API:
 * @see https://github.com/gitlabhq/gitlabhq/blob/master/doc/api/merge_requests.md
 * @method array listMergeRequests
 * @method array singleMergeRequest
 * @method array singleMergeRequestChanges
 * @method array createMergeRequest
 * @method array createMergeRequestComment
 * @method array listMergeRequestComments
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
        $required = [ 'base_url', 'api_token' ];
        $config = Collection::fromConfig($config, $default = [], $required);
        $config['base_url'] = self::completeBaseUrl($config['base_url']);

        $descriptionArray = Yaml::parse(__DIR__ . '/service.yml');
        $description = new Description($descriptionArray);

        $client = new Client($config->toArray());
        $client->setDefaultOption('headers/accept', 'application/json');

        $privateTokenPlugin = new PrivateTokenPlugin($config['api_token']);
        $client->getEmitter()->attach($privateTokenPlugin);

        return new self($client, $description);
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

    /**
     * @param array $parameters
     * @return \GuzzleHttp\Message\ResponseInterface
     * @throws RequestException When an error is encountered
     */
    public function updateMergeRequest(array $parameters)
    {
        return $this->executeRequestWithBody('updateMergeRequest', $parameters);
    }

    /**
     * @param array $parameters
     * @return \GuzzleHttp\Message\ResponseInterface
     * @throws RequestException When an error is encountered
     */
    public function acceptMergeRequest(array $parameters)
    {
        return $this->executeRequestWithBody('acceptMergeRequest', $parameters);
    }

    /**
     * This is a hack to allow guzzle PUT requests to have a body
     * TODO submit issue request for that
     */
    private function executeRequestWithBody($commandName, array $parameters)
    {
        $parameters['request_options'] = [
            'body' => [],
        ];

        /** @var Command $command */
        $command = $this->getCommand($commandName, $parameters);
        return $this->execute($command);
    }
}
