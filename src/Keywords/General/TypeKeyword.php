<?php

namespace BasilLangevin\LaravelDataJsonSchemas\Keywords\General;

use BasilLangevin\LaravelDataJsonSchemas\Enums\DataType;
use BasilLangevin\LaravelDataJsonSchemas\Keywords\Keyword;
use Illuminate\Support\Collection;

class TypeKeyword extends Keyword
{
    public function __construct(protected string|DataType $value) {}

    /**
     * {@inheritdoc}
     */
    public function get(): string|DataType
    {
        return $this->value;
    }

    /**
     * {@inheritdoc}
     */
    public function apply(Collection $schema): Collection
    {
        $type = $this->get();

        if ($type instanceof DataType) {
            $type = $type->value;
        }

        return $schema->merge([
            'type' => $type,
        ]);
    }
}
