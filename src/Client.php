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

    /** @var array */
    private $requiredHttpHeaders;

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
     * METHOD headers are required, so these headers are automatically applied.)
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
     *       'timeout' => 5
     *   ),
     *   'ssl' => array(
     *       'verify_peer' => false,
     *       'verify_peer_name' => false
     *   )
     * );
     *
     * See:
     * @link http://php.net/manual/en/context.http.php HTTP context options
     * @link http://php.net/manual/en/context.ssl.php SSL context options
     */
    public function __construct($uri, $headers = null, $options = null)
    {
        $this->requiredHttpHeaders = array(
            'Accept' => self::$CONTENT_TYPE,
            'Content-Type' => self::$CONTENT_TYPE,
            'Connection' => self::$CONNECTION_TYPE
        );

        $headers = array_merge(self::validHeaders($headers), $this->requiredHttpHeaders);
        $options = self::validOptions($options);
        $context = self::getContext($options);
        $client = new JsonRpc\Client();

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

    /**
     * View the HTTP headers that will be sent on each request.
     *
     * @return array
     * An associative array containing the raw HTTP headers that will be sent
     * with each request.
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * Set add an additional HTTP header. This additional header will be sent
     * on each future HTTP request.
     *
     * @param string $name
     * The name of the HTTP header (e.g. "Authorization").
     *
     * @param string $value
     * The value of this HTTP header (e.g. "Basic YmFzaWM6YXV0aGVudGljYXRpb24=").
     *
     * @return boolean
     * True iff the header has been set successfully (or has had the desired
     * value all along). Note that the CONTENT_TYPE, CONNECTION_TYPE, and
     * METHOD headers cannot be changed, because those headers are required.
     */
    public function setHeader($name, $value)
    {
        if (!self::isValidHeader($name, $value)) {
            return false;
        }

        if (isset($this->requiredHttpHeaders[$name])) {
            return $this->requiredHttpHeaders[$name] === $value;
        }

        $this->headers[$name] = $value;

        return true;
    }

    /**
     * Unset an existing HTTP header. This HTTP header will no longer be sent
     * on future requests.
     *
     * @param string $name
     * The name of the HTTP header (e.g. "Authorization").
     *
     * @return boolean
     * True iff the header was successfully removed (or was never set in the
     * first place). Note that the CONTENT_TYPE, CONNECTION_TYPE, and METHOD
     * headers are required, so those headers cannot be unset.
     */
    public function unsetHeader($name)
    {
        if (!self::isValidHeaderName($name)) {
            return true;
        }

        if (isset($this->requiredHttpHeaders[$name])) {
            return false;
        }

        unset($this->headers[$name]);

        return true;
    }

    private function execute($content)
    {
        $headers = $this->headers;
        $headers['Content-Length'] = strlen($content);
        $header = self::getHeaderText($headers);

        $options = array(
            'http' => array(
                'method' => self::$METHOD,
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

        foreach ($input as $name => $value) {
            if (!self::isValidHeader($name, $value)) {
                return false;
            }
        }

        return true;
    }

    private static function isValidHeader($name, $value)
    {
        return self::isValidHeaderName($name) && self::isValidHeaderValue($value);
    }

    private static function isValidHeaderName($name)
    {
        return is_string($name) && (0 < strlen($name));
    }

    private static function isValidHeaderValue($value)
    {
        return is_string($value) && (0 < strlen($value));
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

        if (is_resource($context)) {
            return $context;
        }

        return stream_context_create();
    }

    private static function getHeaderText($headers)
    {
        $header = '';

        foreach ($headers as $name => $value) {
            $header .= "{$name}: {$value}\r\n";
        }

        return $header;
    }
}
