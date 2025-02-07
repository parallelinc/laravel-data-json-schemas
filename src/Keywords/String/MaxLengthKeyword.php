<?php

namespace BasilLangevin\LaravelDataJsonSchemas\Keywords\String;

use BasilLangevin\LaravelDataJsonSchemas\Keywords\Contracts\HandlesMultipleInstances;
use BasilLangevin\LaravelDataJsonSchemas\Keywords\Keyword;
use Illuminate\Support\Collection;

class MaxLengthKeyword extends Keyword implements HandlesMultipleInstances
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
        return $schema->merge(['maxLength' => $this->value]);
    }

    /**
     * {@inheritdoc}
     */
    public static function applyMultiple(Collection $schema, Collection $instances): Collection
    {
        return $schema->merge(['maxLength' => $instances->min->get()]);
    }
}
