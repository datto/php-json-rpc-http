<?php

namespace Datto\JsonRpc\Http\Examples\Authenticated;

require dirname(dirname(dirname(__DIR__))) . '/vendor/autoload.php';

$evaluator = new Evaluator();
$server = new Server($evaluator);

$server->reply();
