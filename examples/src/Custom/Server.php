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

namespace Datto\JsonRpc\Http\Examples\Custom;

use Datto\JsonRpc;
use Datto\JsonRpc\Http;
use Datto\JsonRpc\Http\Examples\Math;

class Server extends Http\Server
{
    public function __construct()
    {
        $interpreter = array($this, 'getCallable');

        parent::__construct($interpreter);
    }

    public function cmdAdd($a, $b)
    {
        // Allow unauthenticated requests
        return Math::add($a, $b);
    }

    public function cmdSubtract($arguments)
    {
        // Require authentication
        if (!self::isAuthenticated()) {
            self::errorUnauthenticated();
        }

        $minuend = @$arguments['minuend'];
        $subtrahend = @$arguments['subtrahend'];

        return Math::subtract($minuend, $subtrahend);
    }

    public function getCallable($method)
    {
        return array($this, 'cmd' . ucfirst($method));
    }

    private static function isAuthenticated()
    {
        $username = @$_SERVER['PHP_AUTH_USER'];
        $password = @$_SERVER['PHP_AUTH_PW'];

        return ($username === 'username') && ($password === 'password');
    }

    private static function errorUnauthenticated()
    {
        header('WWW-Authenticate: Basic realm="My Realm"');
        header('HTTP/1.1 401 Unauthorized');
        exit();
    }
}
