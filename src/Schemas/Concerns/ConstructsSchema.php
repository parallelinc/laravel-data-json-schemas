<?php

namespace BasilLangevin\LaravelDataSchemas\Schemas\Concerns;

trait ConstructsSchema
{
    public function __construct() {}

    public static function make(): static
    {
        return new static;
    }
}
