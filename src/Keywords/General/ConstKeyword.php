<?php

namespace BasilLangevin\LaravelDataJsonSchemas\Keywords\General;

use BasilLangevin\LaravelDataJsonSchemas\Keywords\Keyword;
use Illuminate\Support\Collection;

class ConstKeyword extends Keyword
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
            'const' => $this->get(),
        ]);
    }
}
