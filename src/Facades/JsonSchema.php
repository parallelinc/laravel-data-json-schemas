<?php

namespace BasilLangevin\LaravelDataJsonSchemas\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \BasilLangevin\LaravelDataJsonSchemas\LaravelDataJsonSchemas
 */
class JsonSchema extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \BasilLangevin\LaravelDataJsonSchemas\JsonSchema::class;
    }
}
