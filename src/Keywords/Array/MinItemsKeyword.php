<?php

namespace BasilLangevin\LaravelDataJsonSchemas\Keywords\Array;

use BasilLangevin\LaravelDataJsonSchemas\Keywords\Contracts\HandlesMultipleInstances;
use BasilLangevin\LaravelDataJsonSchemas\Keywords\Keyword;
use Illuminate\Support\Collection;

class MinItemsKeyword extends Keyword implements HandlesMultipleInstances
{
    public function __construct(protected int $value) {}

    /**
     * {@inheritdoc}
     */
    public function get(): int
    {
        return $this->value;
    }

    /**
     * {@inheritdoc}
     */
    public function apply(Collection $schema): Collection
    {
        return $schema->merge(['minItems' => $this->value]);
    }

    /**
     * {@inheritdoc}
     */
    public static function applyMultiple(Collection $schema, Collection $instances): Collection
    {
        return $schema->merge(['minItems' => $instances->max->get()]);
    }
}
