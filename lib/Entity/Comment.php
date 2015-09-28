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

class Comment implements ArrayParsable
{
    /** @var string */
    public $note;

    /** @var User */
    public $author;

    public static function fromArray(array $data)
    {
        $comment = new self();

        $comment->note = $data['note'];
        $comment->author = User::fromArray($data['author']);

        return $comment;
    }
}
