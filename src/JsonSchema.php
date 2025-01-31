<?php

namespace BasilLangevin\LaravelDataSchemas;

use BasilLangevin\LaravelDataSchemas\Actions\TransformDataClassToSchema;
use BasilLangevin\LaravelDataSchemas\Schemas\ArraySchema;
use BasilLangevin\LaravelDataSchemas\Schemas\Contracts\Schema;
use BasilLangevin\LaravelDataSchemas\Schemas\ObjectSchema;
use BasilLangevin\LaravelDataSchemas\Support\SchemaTree;

class JsonSchema
{
    /**
     * The Spatie Data class to transform into a JSON Schema.
     */
    protected string $dataClass;

    public function __construct() {}

    /**
     * Transform a Spatie Data class into a JSON Schema.
     */
    public function make(string $dataClass): ObjectSchema
    {
        $schema = TransformDataClassToSchema::run($dataClass);

        if ($dialect = config('data-schemas.dialect')) {
            $schema->dialect($dialect);
        }

        return $schema;
    }

    /**
     * Wrap the JSON Schema for a Spatie Data class in an ArraySchema.
     */
    public function collect(string $dataClass): ArraySchema
    {
        $tree = app(SchemaTree::class);

        $schema = ArraySchema::make()->tree($tree)
            ->items(TransformDataClassToSchema::run($dataClass, $tree));

        if ($dialect = config('data-schemas.dialect')) {
            $schema->dialect($dialect);
        }

        return $schema;
    }

    /**
     * Transform the JSON Schema for a Spatie Data class into an array.
     */
    public function toArray(string $dataClass): array
    {
        return $this->make($dataClass)->toArray();
    }

    /**
     * Transform the array-wrapped JSON Schema for a Spatie Data class into an array.
     */
    public function collectToArray(string $dataClass): array
    {
        return $this->collect($dataClass)->toArray();
    }
}
