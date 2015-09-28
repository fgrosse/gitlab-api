#!/bin/php
<?php
/*
 * This file is part of fgrosse/gitlab-api.
 *
 * Copyright © Friedrich Große <friedrich.grosse@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Gitlab\Client\GitlabGuzzleClient;
use Gitlab\Client\HttpGitlabClient;

include __DIR__.'/../vendor/autoload.php';
include __DIR__.'/cli_helper.php';

$baseUrl = getParameter('base-url', $argv);
$token   = getParameter('token', $argv);
$project = getParameter('project', $argv);

try {
    $guzzleClient = GitlabGuzzleClient::factory([
        'base_url'  => $baseUrl,
        'api_token' => $token,
    ]);

    $client = new HttpGitlabClient($guzzleClient);

    $mergeRequests = $client->listMergeRequests($project,
        $state='closed',
        $order='updated_at',
        $sort='asc',
        $page=1, $perPage=5
    );

    foreach ($mergeRequests as $mergeRequest) {
        echo $mergeRequest . PHP_EOL;
    }
} catch (Exception $exception) {
    printError('An Exception of type '.get_class($exception).' occurred:');
    printError($exception->getMessage());
    exit(1);
}
