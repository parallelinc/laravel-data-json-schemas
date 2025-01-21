<?php

namespace BasilLangevin\LaravelDataSchemas\Schemas\Concerns;

use BasilLangevin\LaravelDataSchemas\Support\Concerns\PipeCallbacks;
use BasilLangevin\LaravelDataSchemas\Support\Concerns\WhenCallbacks;
use Illuminate\Support\Collection;

/**
 * @property static DataType $type The type of the schema.
 */
trait PrimitiveSchema
{
    use ConstructsSchema;
    use HasKeywords;
    use PipeCallbacks;
    use WhenCallbacks;

    /**
     * Clone the base structure of the schema.
     */
    public function cloneBaseStructure(): self
    {
        return new static;
    }

    /**
     * Convert the schema to an array.
     */
    public function toArray(): array
    {
        return collect($this->getKeywords())
            ->flatten()
            ->filter(fn (string $keyword) => $this->hasKeyword($keyword))
            ->reduce(function (Collection $schema, string $keyword) {
                return $this->applyKeyword($keyword, $schema);
            }, collect())
            ->toArray();
    }
}
