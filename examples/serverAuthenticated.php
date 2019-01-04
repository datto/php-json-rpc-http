<?php

namespace Datto;

require __DIR__ . '/vendor/autoload.php';

use Example\Evaluator;
use Example\AuthenticatedServer;

$evaluator = new Evaluator();
$server = new AuthenticatedServer($evaluator);

$server->reply();
