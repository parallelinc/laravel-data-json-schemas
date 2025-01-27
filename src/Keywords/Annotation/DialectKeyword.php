<?php

namespace BasilLangevin\LaravelDataSchemas\Keywords\Annotation;

use BasilLangevin\LaravelDataSchemas\Enums\JsonSchemaDialect;
use BasilLangevin\LaravelDataSchemas\Keywords\Keyword;
use Illuminate\Support\Collection;

class DialectKeyword extends Keyword
{
    public function __construct(protected JsonSchemaDialect $value) {}

    /**
     * Get the value of the keyword.
     */
    public function get(): JsonSchemaDialect
    {
        return $this->value;
    }

    /**
     * Add the definition for the keyword to the given schema.
     */
    public function apply(Collection $schema): Collection
    {
        return $schema->merge([
            '$schema' => $this->get()->value,
        ]);
    }
}
