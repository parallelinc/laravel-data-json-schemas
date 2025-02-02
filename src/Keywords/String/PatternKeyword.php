<?php

namespace BasilLangevin\LaravelDataSchemas\Keywords\String;

use BasilLangevin\LaravelDataSchemas\Keywords\Contracts\MergesMultipleInstancesIntoAllOf;
use BasilLangevin\LaravelDataSchemas\Keywords\Keyword;
use Illuminate\Support\Collection;

class PatternKeyword extends Keyword implements MergesMultipleInstancesIntoAllOf
{
    public function __construct(protected string $value) {}

    /**
     * {@inheritdoc}
     */
    public function get(): string
    {
        return $this->value;
    }

    /**
     * {@inheritdoc}
     */
    public function apply(Collection $schema): Collection
    {
        return $schema->merge([
            'pattern' => $this->value,
        ]);
    }
}
