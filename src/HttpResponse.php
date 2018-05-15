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

class HttpResponse
{
    /** @var string|null */
    private $version;

    /** @var integer|null */
    private $statusCode;

    /** @var string|null */
    private $reason;

    /** @var array|null */
    private $headers;

    public function __construct(array $http_response_header)
    {
        $statusLine = array_shift($http_response_header);

        $this->extractStatusLine($statusLine);
        $this->extractHeaders($http_response_header);
    }

    private function extractStatusLine($input)
    {
        $delimiter = "\x03";
        $expression = 'HTTP/(?<version>[0-9.]+) (?<statusCode>[0-9]+) ?(?<reason>.*)';
        $pattern = "{$delimiter}{$expression}{$delimiter}XDs";

        if (preg_match($pattern, $input, $match) === 1) {
            $this->version = $match['version'];
            $this->statusCode = (integer)$match['statusCode'];
            $this->reason = $match['reason'];
        }
    }

    private function extractHeaders(array $input)
    {
        $this->headers = array();

        foreach ($input as $header) {
            list($name, $value) = explode(':', $header, 2);

            $this->headers[$name] = trim($value);
        }

        return $this->headers;
    }

    public function getVersion()
    {
        return $this->version;
    }

    public function getStatusCode()
    {
        return $this->statusCode;
    }

    public function getReason()
    {
        return $this->reason;
    }

    public function getHeaders()
    {
        return $this->headers;
    }
}
