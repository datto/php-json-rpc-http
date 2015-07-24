<?php

require __DIR__ . '/../../../vendor/autoload.php';

use Datto\JsonRpc\Http\Examples\Basic\Server;

$server = new Server();

$server->reply();
