<?php

require __DIR__ . '/../../../vendor/autoload.php';

use Datto\JsonRpc\Http\Examples\Custom\Server;

$server = new Server();

$server->reply();
