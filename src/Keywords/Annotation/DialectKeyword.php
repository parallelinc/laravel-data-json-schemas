<?php

namespace BasilLangevin\LaravelDataSchemas\Keywords\Annotation;

use BasilLangevin\LaravelDataSchemas\Enums\JsonSchemaDialect;
use BasilLangevin\LaravelDataSchemas\Keywords\Keyword;
use Illuminate\Support\Collection;

class DialectKeyword extends Keyword
{
    public function __construct(protected JsonSchemaDialect $value) {}

    /**
     * {@inheritdoc}
     */
    public function get(): JsonSchemaDialect
    {
        return $this->value;
    }

    /**
     * {@inheritdoc}
     */
    public function apply(Collection $schema): Collection
    {
        return $schema->merge([
            '$schema' => $this->get()->value,
        ]);
    }
}
