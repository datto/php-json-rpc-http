<?php

require dirname(__DIR__) . '/vendor/autoload.php';

use Datto\JsonRpc\Http\Server;
use Datto\JsonRpc\Http\Examples\Simple\Evaluator;

$evaluator = new Evaluator();
$server = new Server($evaluator);

$server->reply();
