<?php

/**
 * Copyright (C) 2015 Datto, Inc.
 *
 * This file is part of PHP JSON-RPC HTTP.
 *
 * PHP JSON-RPC HTTP is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License, version 3,
 * as published by the Free Software Foundation.
 *
 * PHP JSON-RPC HTTP is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with PHP JSON-RPC HTTP. If not, see <http://www.gnu.org/licenses/>.
 *
 * @author Spencer Mortensen <smortensen@datto.com>
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL-3.0
 * @copyright 2015 Datto, Inc.
 */

namespace Datto\JsonRpc\Http;

use Datto\JsonRpc;

class Client
{
    /** @var string */
    private static $METHOD = 'POST';

    /** @var string */
    private static $CONTENT_TYPE = 'application/json';

    /** @var string */
    private $uri;

    /** @var array */
    private $headers;

    /** @var JsonRpc\Client */
    private $client;

    public function __construct($uri, $headers = null)
    {
        $this->uri = $uri;
        $this->headers = self::getHeaders($headers);
        $this->client = new JsonRpc\Client();
    }

    public function notify($method, $arguments = null)
    {
        $this->client->notify($method, $arguments);
    }

    public function query($id, $method, $arguments = null)
    {
        $this->client->query($id, $method, $arguments);
    }

    public function send()
    {
        $content = $this->client->encode();
        $reply = $this->execute(self::$METHOD, $this->headers, $content);
        return $this->client->decode($reply);
    }

    private static function getHeaders($headers)
    {
        if (!self::isValidHeaders($headers)) {
            $headers = array();
        }

        $headers['Accept'] = self::$CONTENT_TYPE;
        $headers['Content-Type'] = self::$CONTENT_TYPE;

        return $headers;
    }

    private static function isValidHeaders($input)
    {
        if (!is_array($input)) {
            return false;
        }

        foreach ($input as $name => $value) {
            if (!is_string($name) || (strlen($name) === 0)) {
                return false;
            }

            if (!is_string($value) || (strlen($value) === 0)) {
                return false;
            }
        }

        return true;
    }

    private function execute($method, $headers, $content)
    {
        $headers['Content-Length'] = strlen($content);

        $header = self::formatHeader($headers);

        $options = array(
            'http' => array(
                'method' => $method,
                'header' => $header,
                'content' => $content
            )
        );

        $context = stream_context_create($options);
        $reply = @file_get_contents($this->uri, false, $context);

        if ($reply === false) {
            $reply = null;
        }

        return $reply;
    }

    private static function formatHeader($headers)
    {
        $header = '';

        foreach ($headers as $name => $value) {
            $header .= "{$name}: {$value}\r\n";
        }

        return $header;
    }
}
