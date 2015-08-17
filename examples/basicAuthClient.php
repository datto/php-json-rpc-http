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

require __DIR__ . '/../vendor/autoload.php';

use Datto\JsonRpc\Http\Examples\Authentication\Basic\Client;


# With valid credentials:

$client = new Client('http://json-rpc-http/basicAuthServer.php', 'username', 'password');

$client->query(1, 'add', array(1, 2));

$reply = $client->send();

echo "With valid credentials:\n";
print_r($reply); // array('jsonrpc' => '2.0', 'id' => 1, 'result' => 3)


# With invalid credentials:

$client = new Client('http://json-rpc-http/authentication/basic/server.php', 'invalid', 'invalid');

$client->query(1, 'add', array(1, 2));

echo "\n\nWith invalid credentials:\n";
$reply = $client->send();

var_dump($reply); // null
