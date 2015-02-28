#!/bin/php
<?php

use Gitlab\Client\GitlabClient;

include __DIR__ . '/../vendor/autoload.php';
include __DIR__ . '/cli_helper.php';

$baseUrl = getParameter('base-url', $argv);
$token   = getParameter('token', $argv);
$project = getParameter('project', $argv);

try {
    $client = GitlabClient::factory([
        'base_url'  => $baseUrl,
        'api_token' => $token,
    ]);

    $mergeRequests = $client->listMergeRequests([
        'project_id' => $project,
        'state'      => 'closed',
        'order_by'   => 'updated_at',
        'sort'       => 'asc',
        'page'       => 0,
        'per_page'   => 5,
    ]);

    var_dump($mergeRequests);
} catch (Exception $exception) {
    printError("An Exception of type " . get_class($exception) . ' occurred:');
    printError($exception->getMessage());
    exit(1);
}
