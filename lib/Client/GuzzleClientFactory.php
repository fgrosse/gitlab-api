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

use Exception;
use Gitlab\Utils\StringUtil;
use GuzzleHttp\Client;
use GuzzleHttp\Collection;
use GuzzleHttp\Command\Guzzle\Description;
use Symfony\Component\Yaml\Parser as YamlParser;

/**
 * The GuzzleClientFactory can created fully configured instances of a GitlabClient.
 * The client is configured using the service definitions from the API folder.
 */
class GuzzleClientFactory
{
    /**
     * Create a new instance of GitlabClient.
     * @param array $config
     * @return GitlabGuzzleClient
     */
    public static function createClient($config = [])
    {
        $default = [
            'ssl.certificate_authority' => 'system',
        ];
        $required = ['base_url', 'api_token'];
        $config = Collection::fromConfig($config, $default, $required);
        $config['base_url'] = self::completeBaseUrl($config['base_url']);

        $serviceDescriptionFilePath = __DIR__.'/ServiceDescription/service_description.yml';
        $definition = self::loadServiceDefinition($serviceDescriptionFilePath);
        self::emulateGuzzle3ResponseModels($definition);
        $description = new Description($definition);

        $client = new Client($config->toArray());
        $client->setDefaultOption('headers/accept', 'application/json');

        $privateTokenPlugin = new PrivateTokenPlugin($config['api_token']);
        $client->getEmitter()->attach($privateTokenPlugin);

        $gitlabClient = new GitlabGuzzleClient($client, $description);
        $gitlabClient->getEmitter()->attach(new ResponseClassProcessor($description));
        return $gitlabClient;
    }

    private static function completeBaseUrl($originalBaseUrl)
    {
        $baseUrl = $originalBaseUrl;
        if (StringUtil::endsWith($baseUrl, '/') == false) {
            $baseUrl .= '/';
        }

        $baseUrlPath = parse_url($baseUrl, PHP_URL_PATH);
        if (StringUtil::endsWith($baseUrlPath, '/api/v3/')) {
            return $baseUrl;
        }

        return $baseUrl.'api/v3/';
    }

    private static function loadServiceDefinition($serviceDescriptionFilePath)
    {
        $parser = new YamlParser();
        $fileContents = self::loadFileContents($serviceDescriptionFilePath);
        $descriptionArray = $parser->parse($fileContents);

        if (isset($descriptionArray['imports'])) {
            self::loadServiceDefinitionImports($serviceDescriptionFilePath, $descriptionArray);
        }

        return $descriptionArray;
    }

    private static function loadFileContents($path)
    {
        $content = file_get_contents($path);
        if ($content === false) {
            throw new Exception("Could not file from path \"$path\"");
        }

        return $content;
    }

    private static function loadServiceDefinitionImports($serviceDescriptionFilePath, array &$descriptionArray)
    {
        foreach ($descriptionArray['imports'] as $apiDescriptionFile) {
            $importedDescriptionFile = dirname($serviceDescriptionFilePath)."/$apiDescriptionFile";
            $importedDescription = self::loadServiceDefinition($importedDescriptionFile);
            $descriptionArray = array_merge_recursive($descriptionArray, $importedDescription);
        }
        unset($descriptionArray['imports']);
    }

    private static function emulateGuzzle3ResponseModels(array &$descriptionArray)
    {
        if (!isset($descriptionArray['models']['jsonResponse'])) {
            $descriptionArray['models']['jsonResponse'] = [
                'type' => 'object',
                'additionalProperties' => ['location' => 'json'],
            ];
        }

        foreach ($descriptionArray['operations'] as &$operation) {
            if (empty($operation['responseModel'])) {
                $operation['responseModel'] = 'jsonResponse';
            }

            if (isset($operation['responseClass'])) {
                $operation['responseParser'] = $operation['responseClass'];
                unset($operation['responseClass']);
            }
        }
    }
}
