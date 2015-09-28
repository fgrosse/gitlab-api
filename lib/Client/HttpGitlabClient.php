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
}
