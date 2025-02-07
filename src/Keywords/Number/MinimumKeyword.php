<?php

namespace BasilLangevin\LaravelDataJsonSchemas\Keywords\Number;

use BasilLangevin\LaravelDataJsonSchemas\Keywords\Contracts\HandlesMultipleInstances;
use BasilLangevin\LaravelDataJsonSchemas\Keywords\Keyword;
use Illuminate\Support\Collection;

class MinimumKeyword extends Keyword implements HandlesMultipleInstances
{
    public function __construct(protected int|float $value) {}

    /**
     * {@inheritdoc}
     */
    public function get(): int|float
    {
        return $this->value;
    }

    /**
     * {@inheritdoc}
     */
    public function apply(Collection $schema): Collection
    {
        return $schema->merge(['minimum' => $this->value]);
    }

    /**
     * {@inheritdoc}
     */
    public static function applyMultiple(Collection $schema, Collection $instances): Collection
    {
        return $schema->merge(['minimum' => $instances->max->get()]);
    }
}
