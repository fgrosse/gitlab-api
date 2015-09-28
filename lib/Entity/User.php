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

class User implements ArrayParsable
{
    /** @var int */
    public $id;

    /** @var string */
    public $username;

    /** @var string */
    public $name;

    /** @var string */
    public $state;

    /** @var string */
    public $avatarUrl;

    public static function fromArray(array $data)
    {
        $user = new self();

        $user->id = $data['id'];
        $user->username = $data['username'];
        $user->name = $data['name'];

        if (isset($data['state'])) {
            $user->state = $data['state'];
        }

        if (isset($data['avatar_url'])) {
            $user->avatarUrl = $data['avatar_url'];
        }

        return $user;
    }
}
