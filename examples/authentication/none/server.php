<?php

require __DIR__ . '/../../../vendor/autoload.php';

use Datto\JsonRpc\Http\Server;

$interpreter = function ($method) {
    return "\\Datto\\JsonRpc\\Http\\Examples\\Math::{$method}";
};

$server = new Server($interpreter);

$server->reply();
