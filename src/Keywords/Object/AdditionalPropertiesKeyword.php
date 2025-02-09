<?php

namespace BasilLangevin\LaravelDataJsonSchemas\Keywords\Object;

use BasilLangevin\LaravelDataJsonSchemas\Keywords\Keyword;
use Illuminate\Support\Collection;

class AdditionalPropertiesKeyword extends Keyword
{
    public function __construct(protected bool $value) {}

    /**
     * {@inheritdoc}
     */
    public function get(): bool
    {
        return $this->value;
    }

    /**
     * {@inheritdoc}
     */
    public function apply(Collection $schema): Collection
    {
        return $schema->merge(['additionalProperties' => $this->value]);
    }
}
