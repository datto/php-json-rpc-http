<?php

require __DIR__ . '/vendor/autoload.php';

use Example\Evaluator;
use Datto\JsonRpc\Http\Server;

$evaluator = new Evaluator();
$server = new Server($evaluator);

$server->reply();
