<?php

require __DIR__ . '/../vendor/autoload.php';

use Datto\JsonRpc\Http\Example\Server\Translator;
use Datto\JsonRpc\Http\Server;

$translator = new Translator();
$server = new Server($translator);

$server->reply();
