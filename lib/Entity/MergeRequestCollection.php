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

use ArrayAccess;
use ArrayIterator;
use Gitlab\Client\ArrayParsable;
use IteratorAggregate;
use LogicException;

class MergeRequestCollection implements ArrayParsable, ArrayAccess, IteratorAggregate
{
    private $mergeRequests = [];

    /**
     * Parse a MergeRequestCollection from an array
     * @param array $data
     * @return MergeRequestCollection|MergeRequest[]
     */
    public static function fromArray(array $data)
    {
        $collection = new self();
        foreach ($data as $mergeRequestData) {
            $collection[] = MergeRequest::fromArray($mergeRequestData);
        }

        return $collection;
    }

    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->mergeRequests);
    }

    public function offsetGet($offset)
    {
        return $this->mergeRequests[$offset];
    }

    public function offsetSet($offset, $value)
    {
        if ($value instanceof MergeRequest == false) {
            throw new LogicException('Can not set an instance of ' . get_class($value) . ' in ' . get_class($this) );
        }

        if ($offset === null) {
            $this->mergeRequests[] = $value;
        } else {
            $this->mergeRequests[$offset] = $value;
        }
    }

    public function offsetUnset($offset)
    {
        unset($this->mergeRequests[$offset]);
    }

    public function asArray()
    {
        return $this->mergeRequests;
    }

    public function getIterator()
    {
        return new ArrayIterator($this->mergeRequests);
    }
}
