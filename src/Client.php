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

/**
 * Class Client
 *
 * @package Datto\JsonRpc\Http
 */
class Client
{
    /** @var string */
    private static $METHOD = 'POST';

    /** @var string */
    private static $CONTENT_TYPE = 'application/json';

    /** @var string */
    private static $CONNECTION_TYPE = 'close';

    /** @var string */
    private $uri;

    /** @var array */
    private $headers;

    /** @var resource */
    private $context;

    /** @var JsonRpc\Client */
    private $client;

    /**
     * Construct a JSON-RPC 2.0 client. This will allow you to send queries
     * to a remote server.
     *
     * @param string $uri
     * Address of your JSON-RPC 2.0 endpoint.
     *
     * Example:
     * $uri = "https://api.example.com";
     *
     * @param null|array $headers
     * An associative array of the raw HTTP headers that you'd like to send
     * with your request. (Note that the CONTENT_TYPE, CONNECTION_TYPE, and
     * METHOD headers are required, so these headers are set automatically
     * for you.)
     *
     * Example:
     * $headers = array(
     *   'Authorization' => 'Basic YmFzaWM6YXV0aGVudGljYXRpb24='
     * );
     *
     * @param null|array $options
     * An associative array of the PHP stream context options that you'd use.
     *
     * Example:
     * $options = array(
     *   'http' => array(
     *     'timeout' => 5
     *   ),
     *   'ssl' => array(
     *     'verify_peer' => false,
     *     'verify_peer_name' => false
     *   )
     * );
     *
     * See:
     * @link http://php.net/manual/en/context.http.php HTTP context options
     * @link http://php.net/manual/en/context.ssl.php SSL context options
     */
    public function __construct($uri, $headers = null, $options = null)
    {
        $headers = self::validHeaders($headers);
        $options = self::validOptions($options);
        $context = self::getContext($options);
        $client = new JsonRpc\Client();

        self::setHttpOptions($headers, $context);

        $this->uri = $uri;
        $this->headers = $headers;
        $this->context = $context;
        $this->client = $client;
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
        $reply = $this->execute($content);
        return $this->client->decode($reply);
    }

    private function execute($content)
    {
        $headers = $this->headers;
        $headers['Content-Length'] = strlen($content);
        $header = self::getHeader($headers);

        $options = array(
            'http' => array(
                'header' => $header,
                'content' => $content
            )
        );

        if (!stream_context_set_option($this->context, $options)) {
            return null;
        }

        $reply = @file_get_contents($this->uri, false, $this->context);

        if ($reply === false) {
            $reply = null;
        }

        return $reply;
    }

    private static function validHeaders($headers)
    {
        if (!self::isValidHeaders($headers)) {
            $headers = array();
        }

        return $headers;
    }

    private static function isValidHeaders($input)
    {
        if (!is_array($input)) {
            return false;
        }

        foreach ($input as $key => $value) {
            if (!is_string($key) || (strlen($key) === 0)) {
                return false;
            }

            if (!is_string($value) || (strlen($value) === 0)) {
                return false;
            }
        }

        return true;
    }

    private static function validOptions($options)
    {
        if (!is_array($options)) {
            return array();
        }

        $supportedOptions = array(
            'http' => true,
            'ssl' => true
        );

        return array_intersect_key($options, $supportedOptions);
    }

    private static function getContext($options)
    {
        $context = @stream_context_create($options);

        if (!is_resource($context)) {
            return stream_context_create();
        }

        return $context;
    }

    private static function setHttpOptions(&$headers, &$context)
    {
        $headers['Accept'] = self::$CONTENT_TYPE;
        $headers['Content-Type'] = self::$CONTENT_TYPE;
        $headers['Connection'] = self::$CONNECTION_TYPE;

        stream_context_set_option($context, 'http', 'method', self::$METHOD);
    }

    private static function getHeader($headers)
    {
        $header = '';

        foreach ($headers as $name => $value) {
            $header .= "{$name}: {$value}\r\n";
        }

        return $header;
    }
}
