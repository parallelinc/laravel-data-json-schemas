<?php

namespace BasilLangevin\LaravelDataJsonSchemas\Keywords\Annotation;

use BasilLangevin\LaravelDataJsonSchemas\Enums\JsonSchemaDialect;
use BasilLangevin\LaravelDataJsonSchemas\Keywords\Keyword;
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
