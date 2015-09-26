### PHP Gitlab API

[![Build Status](https://secure.travis-ci.org/fgrosse/gitlab-api.png?branch=master)](http://travis-ci.org/fgrosse/gitlab-api)
[![HHVM Status](http://hhvm.h4cc.de/badge/fgrosse/gitlab-api.png)](http://hhvm.h4cc.de/package/fgrosse/gitlab-api)
[![PHP 7 ready](http://php7ready.timesplinter.ch/fgrosse/gitlab-api/badge.svg)](https://travis-ci.org/fgrosse/gitlab-api)
[![Coverage Status](https://coveralls.io/repos/fgrosse/gitlab-api/badge.svg?branch=master&service=github)](https://coveralls.io/github/fgrosse/gitlab-api?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/fgrosse/gitlab-api/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/fgrosse/gitlab-api/?branch=master)

[![Latest Stable Version](https://poser.pugx.org/fgrosse/gitlab-api/v/stable.png)](https://packagist.org/packages/fgrosse/gitlab-api)
[![Total Downloads](https://poser.pugx.org/fgrosse/gitlab-api/downloads.png)](https://packagist.org/packages/fgrosse/gitlab-api)
[![Latest Unstable Version](https://poser.pugx.org/fgrosse/gitlab-api/v/unstable.png)](https://packagist.org/packages/fgrosse/gitlab-api)
[![License](https://poser.pugx.org/fgrosse/gitlab-api/license.png)](https://packagist.org/packages/fgrosse/gitlab-api)


This is a php client for the [gitlab][1] API. This client bases on Guzzle 5 with service descriptions.
You can have a quick overview of the implemented gitlab APIs in the yml files in `lib/Client/ServiceDescription`.

**Work in progress**

### Implemented APIs
 * Commits
 * Issues
 * Merge Requests

## Dependencies

Gitlab-api requires at least `PHP 5.5` and also been successfully tested using `PHP 7` and `HHVM`.

## Installation

The preferred way to install this library is to rely on [Composer][3]:

```bash
$ composer require fgrosse/gitlab-api
```

## Usage

The API is still work in progress and the actual usage might change in the future. For now you can use the `GitlabClient`
directly like this:

```php
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
```

In the future I will probably create a facade around the client which follows a well defined interface.
Until then you need to checkout the [lib/Client/ServiceDescription](lib/Client/ServiceDescription) to see the available
parameters for each API call.

### Not implemented APIs (yet)
 * deploy_key_multiple_projects API
 * deploy_keys API
 * groups API
 * labels API
 * milestones API
 * notes API
 * oauth2 API
 * project_snippets API
 * projects API
 * repositories API
 * repository_files API
 * services API
 * session API
 * system_hooks API
 * users API

## License

This library is distributed under the [MIT License](LICENSE).

[1]: https://about.gitlab.com/
[2]: https://github.com/gitlabhq/gitlabhq/tree/master/doc/api
[3]: https://getcomposer.org/
