<?php

namespace Datto\JsonRpc\Http\Examples\Simple;

use Datto\JsonRpc\Evaluator;
use Datto\JsonRpc\Exception;
use Datto\JsonRpc\Http\Examples\Simple\Library\Math;

class Api implements Evaluator
{
    public function evaluate($method, $arguments)
    {
        if ($method === 'add') {
            return self::add($arguments);
        }

        throw new Exception\Method();
    }

    private static function add($arguments)
    {
        @list($a, $b) = $arguments;

        if (!is_int($a) || !is_int($b)) {
            throw new Exception\Argument();
        }

        return Math::add($a, $b);
    }
}
