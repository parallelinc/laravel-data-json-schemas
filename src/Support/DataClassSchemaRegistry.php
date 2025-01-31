<?php

namespace BasilLangevin\LaravelDataSchemas\Support;

use BasilLangevin\LaravelDataSchemas\Schemas\ObjectSchema;

class DataClassSchemaRegistry
{
    protected array $schemas = [];

    public function __construct()
    {
        $this->schemas = [];
    }

    public function register(string $dataClass, ObjectSchema $schema): void
    {
        $this->schemas[$dataClass] = $schema;
    }

    public function get(string $dataClass): ObjectSchema
    {
        return $this->schemas[$dataClass];
    }

    public function has(string $dataClass): bool
    {
        return isset($this->schemas[$dataClass]);
    }
}
