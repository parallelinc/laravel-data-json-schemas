<?php

namespace BasilLangevin\LaravelDataSchemas\Keywords\Annotation;

use BasilLangevin\LaravelDataSchemas\Keywords\Keyword;
use Illuminate\Support\Collection;

class TitleKeyword extends Keyword
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
            'title' => $this->get(),
        ]);
    }
}
