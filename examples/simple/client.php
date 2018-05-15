<?php

require __DIR__ . '/../../vendor/autoload.php';

use Datto\JsonRpc\Http\Client;
use Datto\JsonRpc\Http\HttpException;

// Construct a client that can query your remote server over HTTP(S):
$client = new Client('http://localhost:8080/');

// Add the numbers "1" and "2":
$client->query(1, 'add', array(1, 2));

try {
    // Receive the number "3":
    $reply = $client->send();

    echo var_export($reply), "\n";

    /*
    array (
        'jsonrpc' => '2.0',
        'id' => 1,
        'result' => 3,
    )
    */
} catch (HttpException $exception) {
    echo "HttpException";

    $httpResponse = $exception->getHttpResponse();

    if ($httpResponse === null) {
        echo " ";
        echo "(see 'README.md' to set up this example)\n";
    } else {
        echo "\n";
        echo " * statusCode: ", $httpResponse->getStatusCode(), "\n";
        echo " * reason: ", $httpResponse->getReason(), "\n";
        echo " * headers: ", json_encode($httpResponse->getHeaders()), "\n";
        echo " * version: ", $httpResponse->getVersion(), "\n";
    }
}
