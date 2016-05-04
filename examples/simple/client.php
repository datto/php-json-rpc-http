<?php

require __DIR__ . '/vendor/autoload.php';

use Datto\JsonRpc\Http\Client;

// Construct a client that can query your remote server over HTTP(S):
$client = new Client('http://json-rpc-http/simple/server.php');

// Add the numbers "1" and "2":
$client->query(1, 'add', array(1, 2));

// Receive the number "3":
print_r($client->send());

/*
Array
(
    [jsonrpc] => 2.0
    [id] => 1
    [result] => 3
)
*/
