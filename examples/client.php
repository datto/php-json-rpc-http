<?php

require __DIR__ . '/../vendor/autoload.php';

use Datto\JsonRpc\Http\Client;

$uri = 'http://json-rpc-http/server.php';

$client = new Client($uri);

$client->query(1, 'add', array(1, 2));

$reply = $client->send();

print_r($reply); // array('jsonrpc' => '2.0', 'id' => 1, 'result' => 3);
