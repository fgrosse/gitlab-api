<?php

namespace Gitlab;


class GitlabClient
{
    private $guzzle;

    public function __construct(GuzzleClient $guzzleClient)
    {
        $this->guzzle = $guzzleClient;
    }

    public function getMergeRequests()
    {
        $parameters = [

        ];

        return $this->guzzle->listMergeRequests($parameters);
    }
}
