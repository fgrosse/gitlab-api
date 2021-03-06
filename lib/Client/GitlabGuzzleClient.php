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

use Gitlab\Entity\CommentCollection;
use Gitlab\Entity\MergeRequest;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Command\Guzzle\DescriptionInterface;
use GuzzleHttp\Command\Guzzle\GuzzleClient;
use GuzzleHttp\Message\ResponseInterface;

/**
 * Class GitlabGuzzleClient is a guzzle client which is configured completely via the service definition (service.yml).
 * Use GitlabClient::factory to create new instances.
 *
 * The following magic methods are available through the service definition:
 *
 * Merge Requests API:
 * @see https://github.com/gitlabhq/gitlabhq/blob/v7.7.0/doc/api/merge_requests.md
 * @see GitlabGuzzleClient::listMergeRequests
 * @see GitlabGuzzleClient::createMergeRequest
 * @see GitlabGuzzleClient::getMergeRequest
 * @see GitlabGuzzleClient::updateMergeRequest
 * @see GitlabGuzzleClient::acceptMergeRequest
 * @method array getMergeRequestChanges($parameters)
 * @method MergeRequest createMergeRequestComment($parameters)
 * @method CommentCollection getMergeRequestComments($parameters)
 *
 * Commits API
 * @see https://github.com/gitlabhq/gitlabhq/blob/v7.7.0/doc/api/commits.md
 * @method array listCommits($parameters)
 * @method array getCommit($parameters)
 * @method array getCommitDiff($parameters)
 * @method array getCommitComments($parameters)
 * @method array createCommitComment($parameters)
 *
 * Issues API
 * @see https://github.com/gitlabhq/gitlabhq/blob/v7.7.0/doc/api/issues.md
 * @method array listIssues($parameters)
 * @method array listProjectIssues($parameters)
 * @method array getIssue($parameters)
 * @method array createIssue($parameters)
 * @method array updateIssue($parameters)
 *
 * Labels API
 * @see https://github.com/gitlabhq/gitlabhq/blob/v7.7.0/doc/api/labels.md
 * @method array listLabels($parameters)
 * @method array createLabel($parameters)
 * @method array updateLabel($parameters)
 * @method array deleteLabel($parameters)
 *
 * TODO: branches API
 * TODO: deploy_key_multiple_projects API
 * TODO: deploy_keys API
 * TODO: groups API
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
class GitlabGuzzleClient extends GuzzleClient
{
    /**
     * Factory method to create a fully configured GitlabClient.
     * @param array $config
     * @return GitlabGuzzleClient
     */
    public static function factory(array $config)
    {
        return GuzzleClientFactory::createClient($config);
    }

    /**
     * A list of operations that are executed with a body.
     * TODO: this is a hack until  https://github.com/guzzle/guzzle-services supports request bodies on PUT or DELETE
     * @var string[]
     */
    private $executeWithBody = [
        'updateIssue',
        'updateLabel',
        'deleteLabel',
    ];

    /**
     * @param ClientInterface $client
     * @param DescriptionInterface $description
     * @param array $config
     * @see factory use the static factory method to retrieve a fully configured gitlab GitlabClient
     */
    public function __construct(ClientInterface $client, DescriptionInterface $description, array $config = [])
    {
        parent::__construct($client, $description, $config);
    }

    public function __call($name, array $arguments)
    {
        if (in_array($name, $this->executeWithBody)) {
            return $this->executeRequestWithBody($name, $arguments[0]);
        }

        return parent::__call($name, $arguments);
    }

    /**
     * This is a hack to allow guzzle PUT or DELETE requests to have a body.
     * TODO submit github issue for that.
     * @param $commandName
     * @param array $parameters
     * @return ResponseInterface
     */
    private function executeRequestWithBody($commandName, array $parameters)
    {
        $parameters['request_options'] = [
            'body' => [],
        ];

        $command = $this->getCommand($commandName, $parameters);
        return $this->execute($command);
    }

    /**
     * @param array $parameters
     * @return MergeRequest[]
     */
    public function listMergeRequests(array $parameters)
    {
        $command = $this->getCommand('listMergeRequests', $parameters);

        /** @var MergeRequest[] $mergeRequests */
        $mergeRequests = $this->execute($command);
        foreach ($mergeRequests as $mergeRequest) {
            $mergeRequest->project = $parameters['project_id'];
        }

        return $mergeRequests;
    }

    /**
     * @param array $parameters
     * @return MergeRequest
     */
    public function getMergeRequest(array $parameters)
    {
        $command = $this->getCommand('getMergeRequest', $parameters);

        /** @var MergeRequest $mergeRequest */
        $mergeRequest = $this->execute($command);
        $mergeRequest->project = $parameters['project_id'];

        return $mergeRequest;
    }

    /**
     * @param array $parameters
     * @return MergeRequest
     */
    public function createMergeRequest(array $parameters)
    {
        $command = $this->getCommand('createMergeRequest', $parameters);

        /** @var MergeRequest $mergeRequest */
        $mergeRequest = $this->execute($command);
        $mergeRequest->project = $parameters['project_id'];

        return $mergeRequest;
    }

    /**
     * @param array $parameters
     * @return MergeRequest
     */
    public function updateMergeRequest(array $parameters)
    {
        /** @var MergeRequest $mergeRequest */
        $mergeRequest = $this->executeRequestWithBody('updateMergeRequest', $parameters);
        $mergeRequest->project = $parameters['project_id'];

        return $mergeRequest;
    }

    /**
     * @param array $parameters
     * @return MergeRequest
     */
    public function acceptMergeRequest(array $parameters)
    {
        /** @var MergeRequest $mergeRequest */
        $mergeRequest = $this->executeRequestWithBody('acceptMergeRequest', $parameters);
        $mergeRequest->project = $parameters['project_id'];

        return $mergeRequest;
    }
}
