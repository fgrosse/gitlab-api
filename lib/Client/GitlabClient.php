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

interface GitlabClient
{
    /**
     * Get all merge requests for this project.
     * The state parameter can be used to get only merge requests with a given state (opened, closed, merged or all).
     * The pagination parameters page and per_page can be used to restrict the list of merge requests.
     * @param string $projectId The ID of a project
     * @param string $state Return 'all' requests or just those that are 'merged', 'opened' or 'closed'
     * @param string $orderBy Return requests ordered by 'created_at' or 'updated_at' fields
     * @param string $sort Return requests sorted in 'asc' or 'desc' order
     * @param int $page
     * @param int $perPage
     * @return \Gitlab\Entity\MergeRequest[]
     */
    public function listMergeRequests($projectId, $state = null, $orderBy = null, $sort = null, $page = null, $perPage = null);

    /**
     * Shows information about the merge request including its files and changes.
     * @param string $projectId
     * @param string $mergeRequestId
     * @return MergeRequest
     */
    public function getMergeRequest($projectId, $mergeRequestId);

    /**
     * Creates a new merge request.
     * @param MergeRequest $mergeRequest
     * @return MergeRequest
     */
    public function createMergeRequest(MergeRequest $mergeRequest);

    /**
     * Updates an existing merge request. You can change branches, title, or even close the MR.
     * @param MergeRequest $mergeRequest
     * @return MergeRequest
     */
    public function updateMergeRequest(MergeRequest $mergeRequest);

    /**
     * Merge changes submitted with MR using this API.
     * @param string $projectId
     * @param int $mergeRequestId
     * @param string|null $commitMessage  Custom merge commit message
     * @return MergeRequest
     */
    public function acceptMergeRequest($projectId, $mergeRequestId, $commitMessage = null);
}
