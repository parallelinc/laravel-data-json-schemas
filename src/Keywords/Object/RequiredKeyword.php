<?php

namespace BasilLangevin\LaravelDataJsonSchemas\Keywords\Object;

use BasilLangevin\LaravelDataJsonSchemas\Keywords\Contracts\HandlesMultipleInstances;
use BasilLangevin\LaravelDataJsonSchemas\Keywords\Keyword;
use Illuminate\Support\Collection;

class RequiredKeyword extends Keyword implements HandlesMultipleInstances
{
    /**
     * @param  array<string>  $value
     */
    public function __construct(protected array $value) {}

    /**
     * {@inheritdoc}
     *
     * @return array<string>
     */
    public function get(): array
    {
        return $this->value;
    }

    /**
     * {@inheritdoc}
     */
    public function apply(Collection $schema): Collection
    {
        return $schema->merge([
            'required' => $this->get(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public static function applyMultiple(Collection $schema, Collection $instances): Collection
    {
        return $schema->merge([
            'required' => $instances->flatMap->get()->unique()->values()->all(),
        ]);
    }
}
