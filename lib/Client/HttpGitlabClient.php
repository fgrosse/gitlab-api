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

use Gitlab\Entity\MergeRequest;

class HttpGitlabClient implements GitlabClient
{
    private $client;

    public function __construct(GitlabGuzzleClient $client)
    {
        $this->client = $client;
    }

    public function listMergeRequests($projectId, $state = null, $orderBy = null, $sort = null, $page = null, $perPage = null)
    {
        return $this->client->listMergeRequests(array_filter([
            'project_id' => $projectId,
            'state'      => $state,
            'order_by'   => $orderBy,
            'sort'       => $sort,
            'page'       => $page,
            'per_page'   => $perPage,
        ]));
    }

    public function getMergeRequest($projectId, $mergeRequestId)
    {
        return $this->client->getMergeRequest(array_filter([
            'project_id' => $projectId,
            'merge_request_id' => $mergeRequestId,
        ]));
    }

    public function createMergeRequest(MergeRequest $mergeRequest)
    {
        return $this->client->createMergeRequest(array_filter([
            'project_id'    => $mergeRequest->project,
            'source_branch' => $mergeRequest->sourceBranch,
            'target_branch' => $mergeRequest->targetBranch,
            'title'         => $mergeRequest->title,
            'description'   => $mergeRequest->description,
            'assignee_id'   => isset($mergeRequest->assignee) ? $mergeRequest->assignee->id : null,
        ]));
    }

    public function updateMergeRequest(MergeRequest $mergeRequest)
    {
        return $this->client->updateMergeRequest(array_filter([
            'project_id'       => $mergeRequest->project,
            'merge_request_id' => $mergeRequest->id,
            'source_branch'    => $mergeRequest->sourceBranch,
            'target_branch'    => $mergeRequest->targetBranch,
            'title'            => $mergeRequest->title,
            'description'      => $mergeRequest->description,
            'assignee_id'      => isset($mergeRequest->assignee) ? $mergeRequest->assignee->id : null,
        ]));
    }

    public function acceptMergeRequest($projectId, $mergeRequestId, $commitMessage = null)
    {
        return $this->client->acceptMergeRequest(array_filter([
            'project_id'           => $projectId,
            'merge_request_id'     => $mergeRequestId,
            'merge_commit_message' => $commitMessage,
        ]));
    }
}
