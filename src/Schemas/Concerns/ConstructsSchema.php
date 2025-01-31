<?php

namespace BasilLangevin\LaravelDataSchemas\Schemas\Concerns;

use BasilLangevin\LaravelDataSchemas\Schemas\Contracts\Schema;

trait ConstructsSchema
{
    public function __construct() {}

    public static function make(): Schema
    {
        return new static;
    }
}
