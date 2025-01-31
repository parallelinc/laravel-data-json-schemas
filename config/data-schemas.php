<?php

use BasilLangevin\LaravelDataSchemas\Enums\JsonSchemaDialect;

return [
    /*
    |--------------------------------------------------------------------------
    | JSON Schema Dialect
    |--------------------------------------------------------------------------
    |
    | If this value is not null, a "$schema" keyword will be set to
    | this identifier in the root of each generated JSON Schema.
    | This value won't change how JSON Schemas are generated.
    */
    'dialect' => JsonSchemaDialect::Draft201909,
];
