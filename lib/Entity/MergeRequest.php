<?php
/*
 * This file is part of fgrosse/gitlab-api.
 *
 * Copyright © Friedrich Große <friedrich.grosse@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gitlab\Entity;

use Gitlab\Client\ArrayParsable;

class MergeRequest implements ArrayParsable
{
    /** @var int */
    public $id;

    /** @var int */
    public $iid;

    /** @var string */
    public $targetBranch;

    /** @var string */
    public $sourceBranch;

    /** @var string */
    public $project;

    /** @var int */
    public $projectId;

    /** @var string */
    public $title;

    /** @var string */
    public $state;

    /** @var int */
    public $upVotes;

    /** @var int */
    public $downVotes;

    /** @var string */
    public $description;

    /** @var User */
    public $author;

    /** @var User|null */
    public $assignee;

    /**
     * Parse a MergeRequest from an array using the format that is returned by gitlab.
     * @param array $data
     * @return MergeRequest
     */
    public static function fromArray(array $data)
    {
        $mergeRequest = new self();

        $mergeRequest->id = $data['id'];
        $mergeRequest->iid = $data['iid'];
        $mergeRequest->targetBranch = $data['target_branch'];
        $mergeRequest->sourceBranch = $data['source_branch'];
        $mergeRequest->projectId = $data['project_id'];
        $mergeRequest->title = $data['title'];
        $mergeRequest->description = $data['description'];
        $mergeRequest->state = $data['state'];
        $mergeRequest->upVotes = $data['upvotes'];
        $mergeRequest->downVotes = $data['downvotes'];
        $mergeRequest->author = User::fromArray($data['author']);

        if (isset($data['assignee'])) {
            $mergeRequest->assignee = User::fromArray($data['assignee']);
        }

        return $mergeRequest;
    }

    /**
     * MergeRequest constructor.
     * @param string $project
     * @param string $title
     * @param int|null $id
     */
    public function __construct($project = '', $title = '', $id = null)
    {
        $this->project = $project;
        $this->title = $title;
        $this->id = $id;
    }

    public function __toString()
    {
        return sprintf('Merge request: %s (%s)', $this->title, $this->getCrossProjectReference());
    }

    public function getCrossProjectReference()
    {
        return sprintf('%s!%d', $this->project, $this->id);
    }
}
