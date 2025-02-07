<?php

namespace BasilLangevin\LaravelDataJsonSchemas\Keywords\Annotation;

use BasilLangevin\LaravelDataJsonSchemas\Keywords\Keyword;
use Illuminate\Support\Collection;

class DefaultKeyword extends Keyword
{
    public function __construct(protected mixed $value) {}

    /**
     * {@inheritdoc}
     */
    public function get(): mixed
    {
        return $this->value;
    }

    /**
     * {@inheritdoc}
     */
    public function apply(Collection $schema): Collection
    {
        return $schema->merge([
            'default' => $this->get(),
        ]);
    }
}
