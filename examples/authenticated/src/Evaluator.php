<?php

/**
 * Copyright (C) 2015 Datto, Inc.
 *
 * This file is part of PHP JSON-RPC HTTP.
 *
 * PHP JSON-RPC HTTP is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License, version 3,
 * as published by the Free Software Foundation.
 *
 * PHP JSON-RPC HTTP is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with PHP JSON-RPC HTTP. If not, see <http://www.gnu.org/licenses/>.
 *
 * @author Spencer Mortensen <smortensen@datto.com>
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL-3.0
 * @copyright 2015 Datto, Inc.
 */

namespace Datto\JsonRpc\Http\Examples\Authenticated;

use Datto\JsonRpc;
use Datto\JsonRpc\Exception;

class Evaluator implements JsonRpc\Evaluator
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
