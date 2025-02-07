<?php

namespace BasilLangevin\LaravelDataJsonSchemas;

use BasilLangevin\LaravelDataJsonSchemas\Actions\TransformDataClassToSchema;
use BasilLangevin\LaravelDataJsonSchemas\Enums\JsonSchemaDialect;
use BasilLangevin\LaravelDataJsonSchemas\Schemas\ArraySchema;
use BasilLangevin\LaravelDataJsonSchemas\Schemas\Contracts\Schema;
use BasilLangevin\LaravelDataJsonSchemas\Schemas\ObjectSchema;
use BasilLangevin\LaravelDataJsonSchemas\Support\SchemaTree;
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

        if ($dialect = config('json-schemas.dialect')) {
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

        if ($dialect = config('json-schemas.dialect')) {
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
