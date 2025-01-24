<?php

namespace BasilLangevin\LaravelDataSchemas\Schemas\Concerns;

use BasilLangevin\LaravelDataSchemas\Enums\DataType;
use BasilLangevin\LaravelDataSchemas\Support\Concerns\PipeCallbacks;
use BasilLangevin\LaravelDataSchemas\Support\Concerns\WhenCallbacks;

/**
 * @property static DataType $type The type of the schema.
 */
trait SingleTypeSchemaTrait
{
    use ConstructsSchema;
    use HasKeywords;
    use PipeCallbacks;
    use WhenCallbacks;

    public static function getDataType(): DataType
    {
        if (! isset(static::$type)) {
            throw new \Exception('SingleType schemas must have a $type.');
        }

        /** @disregard P1014 because the if statement ensures the property exists */
        return static::$type;
    }

    public function applyType(): self
    {
        return $this->type(static::getDataType());
    }

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
        return $this->buildSchema();
    }
}
