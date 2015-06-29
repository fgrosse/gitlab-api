<?php

namespace Gitlab\Client;

interface ResponseParser
{
    /**
     * Unmarshal a response object from an array
     * @param array $data
     * @return mixed
     */
    public static function fromArray(array $data);
}
