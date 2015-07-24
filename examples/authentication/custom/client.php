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

require __DIR__ . '/../../../vendor/autoload.php';

use Datto\JsonRpc\Http\Examples\Custom\Client;


# Add: authentication is optional
echo "add:\n";

## Valid credentials
$client = new Client('http://json-rpc-http/authentication/custom/server.php', 'username', 'password');
$client->query(1, 'add', array(2, 1));
$reply = $client->send();

echo "  Valid credentials: ", json_encode($reply), "\n";
// Valid credentials: {"jsonrpc":"2.0","id":1,"result":3}

## Invalid credentials
$client = new Client('http://json-rpc-http/authentication/custom/server.php', 'invalid', 'invalid');
$client->query(1, 'add', array(2, 1));
$reply = $client->send();

echo "  Invalid credentials: ", json_encode($reply), "\n";
// Invalid credentials: {"jsonrpc":"2.0","id":1,"result":3}

## No credentials
$client = new Client('http://json-rpc-http/authentication/custom/server.php');
$client->query(1, 'add', array(2, 1));
$reply = $client->send();

echo "  No credentials: ", json_encode($reply), "\n\n";
// No credentials: {"jsonrpc":"2.0","id":1,"result":3}


# Subtract
echo "subtract:\n";

## Valid credentials
$client = new Client('http://json-rpc-http/authentication/custom/server.php', 'username', 'password');
$client->query(1, 'subtract', array('minuend' => 2, 'subtrahend' => 1));
$reply = $client->send();

echo "  Valid credentials: ", json_encode($reply), "\n";
// Valid credentials: {"jsonrpc":"2.0","id":1,"result":-1}

## Invalid credentials
$client = new Client('http://json-rpc-http/authentication/custom/server.php', 'invalid', 'invalid');
$client->query(1, 'subtract', array('minuend' => 2, 'subtrahend' => 1));
$reply = $client->send();

echo "  Invalid credentials: ", json_encode($reply), "\n";
// Invalid credentials: null

## No credentials
$client = new Client('http://json-rpc-http/authentication/custom/server.php');
$client->query(1, 'subtract', array('minuend' => 2, 'subtrahend' => 1));
$reply = $client->send();

echo "  No credentials: ", json_encode($reply), "\n";
// No credentials: null
