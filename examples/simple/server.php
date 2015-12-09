<?php

require __DIR__ . '/../../vendor/autoload.php';

use Datto\JsonRpc\Http\Server;
use Datto\JsonRpc\Http\Examples\Simple\Api;


$server = new Server(new Api());

$server->reply();
