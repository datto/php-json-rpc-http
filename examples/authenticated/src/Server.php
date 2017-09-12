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

namespace Datto\JsonRpc\Http\Examples\Authenticated;

use Datto\JsonRpc;
use Datto\JsonRpc\Http;

class Server extends Http\Server
{
    private static $realm = 'My Realm';

    public function reply()
    {
        if (!self::isAuthenticated()) {
            self::errorUnauthenticated();
        }

        parent::reply();
    }

    private static function isAuthenticated()
    {
        $username = @$_SERVER['PHP_AUTH_USER'];
        $password = @$_SERVER['PHP_AUTH_PW'];

        // This is vulnerable to a timing attack, and stores the password in plaintext:
        return ($username === 'username') && ($password === 'password');

        // The "password_verify" function would protect you from both of those issues:
        // http://php.net/manual/en/function.password-verify.php
    }

    private static function errorUnauthenticated()
    {
        header('WWW-Authenticate: Basic realm="'. self::$realm . '"');
        header('HTTP/1.1 401 Unauthorized');
        exit();
    }
}
