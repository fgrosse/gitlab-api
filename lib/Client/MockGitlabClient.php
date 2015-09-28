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

class MockGitlabClient implements GitlabClient
{
    public function listMergeRequests($projectId, $state = null, $orderBy = null, $sort = null, $page = null, $perPage = null)
    {
        return [];
    }
}
