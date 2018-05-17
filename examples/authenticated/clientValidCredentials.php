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

require dirname(dirname(__DIR__)) . '/vendor/autoload.php';

use Datto\JsonRpc\Http\Examples\Authenticated\Client;
use Datto\JsonRpc\Http\HttpException;
use Datto\JsonRpc\Response;

$url = 'http://localhost:8080/';

// Provide a valid username and password:
$username = 'username';
$password = 'password';

// Construct a client that can query your remote server over HTTP(S):
$client = new Client($url, $username, $password);

// Add the numbers "1" and "2":
$client->query(1, 'add', array(1, 2));

try {
    // Receive an array of server responses
    $responses = $client->send();

    printResponses($responses);
} catch (HttpException $exception) {
    $httpResponse = $exception->getHttpResponse();

    echo "HttpException\n";
    echo " * statusCode: ", $httpResponse->getStatusCode(), "\n";
    echo " * reason: ", $httpResponse->getReason(), "\n";
    echo " * headers: ", json_encode($httpResponse->getHeaders()), "\n";
    echo " * version: ", $httpResponse->getVersion(), "\n";
} catch (ErrorException $exception) {
    $message = $exception->getMessage();

    echo "ErrorException\n";
    echo " * message: {$message}\n";
    echo "\n";
    echo "See \"README.md\" to set up this example\n";
}

function printResponses(array $responses)
{
    /**
     * @var Response[] $responses
     */
    foreach ($responses as $response) {
        $id = $response->getId();
        $isError = $response->isError();

        if ($isError) {
            $error = $response->getError();
            $errorProperties = array(
                'code' => $error->getCode(),
                'message' => $error->getMessage(),
                'data' => $error->getData()
            );

            echo "id: {$id}, error: ", json_encode($errorProperties), "\n";
        } else {
            $result = $response->getResult();

            echo "id: {$id}, result: ", json_encode($result), "\n";
        }
    }
}
