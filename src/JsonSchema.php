<?php

namespace BasilLangevin\LaravelDataSchemas;

use BasilLangevin\LaravelDataSchemas\Actions\TransformDataClassToSchema;
use BasilLangevin\LaravelDataSchemas\Enums\JsonSchemaDialect;
use BasilLangevin\LaravelDataSchemas\Schemas\ArraySchema;
use BasilLangevin\LaravelDataSchemas\Schemas\Contracts\Schema;
use BasilLangevin\LaravelDataSchemas\Schemas\ObjectSchema;
use BasilLangevin\LaravelDataSchemas\Support\SchemaTree;
use Spatie\LaravelData\Data;

class JsonSchema
{
    /**
     * Transform a Spatie Data class into a JSON Schema.
     *
     * @param  class-string<Data>  $dataClass
     */
    public function make(string $dataClass): ObjectSchema
    {
        $schema = TransformDataClassToSchema::run($dataClass);

        if ($dialect = config('data-schemas.dialect')) {
            /** @var JsonSchemaDialect $dialect */
            $schema->dialect($dialect);
        }

        return $schema;
    }

    /**
     * Wrap the JSON Schema for a Spatie Data class in an ArraySchema.
     *
     * @param  class-string<Data>  $dataClass
     */
    public function collect(string $dataClass): ArraySchema
    {
        $tree = app(SchemaTree::class);

        $schema = ArraySchema::make()->tree($tree)
            ->items(TransformDataClassToSchema::run($dataClass, $tree));

        if ($dialect = config('data-schemas.dialect')) {
            /** @var JsonSchemaDialect $dialect */
            $schema->dialect($dialect);
        }

        return $schema;
    }

    /**
     * Transform the JSON Schema for a Spatie Data class into an array.
     *
     * @param  class-string<Data>  $dataClass
     * @return array<string, mixed>
     */
    public function toArray(string $dataClass): array
    {
        return $this->make($dataClass)->toArray();
    }

    /**
     * Transform the array-wrapped JSON Schema for a Spatie Data class into an array.
     *
     * @param  class-string<Data>  $dataClass
     * @return array<string, mixed>
     */
    public function collectToArray(string $dataClass): array
    {
        return $this->collect($dataClass)->toArray();
    }
}
