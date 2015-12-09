<?php

require __DIR__ . '/../../vendor/autoload.php';

use Datto\JsonRpc\Http\Examples\Authenticated\BasicAuthentication\Server;
use Datto\JsonRpc\Http\Examples\Authenticated\Api;


$server = new Server(new Api());

$server->reply();
