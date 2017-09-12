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

require __DIR__ . '/vendor/autoload.php';

use Datto\JsonRpc\Http\Examples\Authenticated\Client;

$url = 'http://localhost:8080/';

// Provide an invalid username and password:
$username = 'invalid';
$password = 'invalid';

// Construct a client that can query your remote server over HTTP(S):
$client = new Client($url, $username, $password);

// Add the numbers "1" and "2":
$client->query(1, 'add', array(1, 2));

// Receive a null response:
var_dump($client->send());

/*
NULL
*/
