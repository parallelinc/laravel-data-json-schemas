<?php

namespace BasilLangevin\LaravelDataJsonSchemas\Keywords\Array;

use BasilLangevin\LaravelDataJsonSchemas\Keywords\Contracts\HandlesMultipleInstances;
use BasilLangevin\LaravelDataJsonSchemas\Keywords\Keyword;
use BasilLangevin\LaravelDataJsonSchemas\Schemas\Contracts\Schema;
use Illuminate\Support\Collection;

class ItemsKeyword extends Keyword implements HandlesMultipleInstances
{
    public function __construct(protected Schema $value) {}

    /**
     * {@inheritdoc}
     */
    public function get(): Schema
    {
        return $this->value;
    }

    /**
     * {@inheritdoc}
     */
    public function apply(Collection $schema): Collection
    {
        return $schema->merge(['items' => $this->get()->toArray(true)]);
    }

    /**
     * {@inheritdoc}
     */
    public static function applyMultiple(Collection $schema, Collection $instances): Collection
    {
        return $schema->merge(['items' => [
            'anyOf' => $instances->map->get()->map->toArray(true)->toArray(),
        ]]);
    }
}
