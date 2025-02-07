<?php

namespace BasilLangevin\LaravelDataJsonSchemas\Keywords\Annotation;

use BasilLangevin\LaravelDataJsonSchemas\Keywords\Keyword;
use Illuminate\Support\Collection;

class DescriptionKeyword extends Keyword
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
            'description' => $this->get(),
        ]);
    }
}
