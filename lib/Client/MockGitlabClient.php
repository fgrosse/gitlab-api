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

use Gitlab\Entity\Comment;
use Gitlab\Entity\CommentCollection;
use Gitlab\Entity\MergeRequest;

class MockGitlabClient implements GitlabClient
{
    public function listMergeRequests($projectId, $state = null, $orderBy = null, $sort = null, $page = null, $perPage = null)
    {
        return [];
    }

    public function getMergeRequest($projectId, $mergeRequestId)
    {
        return $this->createMockMergeRequest($projectId, $mergeRequestId);
    }

    public function createMergeRequest(MergeRequest $mergeRequest)
    {
        return $mergeRequest;
    }

    public function updateMergeRequest(MergeRequest $mergeRequest)
    {
        return $mergeRequest;
    }

    public function acceptMergeRequest($projectId, $mergeRequestId, $commitMessage = null)
    {
        return $this->createMockMergeRequest($projectId, $mergeRequestId);
    }

    public function createMergeRequestComment($projectId, $mergeRequestId, $note)
    {
        return new Comment($note);
    }

    public function getMergeRequestComments($projectId, $mergeRequestId)
    {
        return new CommentCollection();
    }

    private function createMockMergeRequest($projectId, $mergeRequestId)
    {
        $mergeRequest = new MergeRequest($projectId, "Mock merge request #$mergeRequestId");
        $mergeRequest->id = $mergeRequestId;

        return $mergeRequest;
    }
}
