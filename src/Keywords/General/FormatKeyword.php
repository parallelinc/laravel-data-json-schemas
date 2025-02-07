<?php

namespace BasilLangevin\LaravelDataJsonSchemas\Keywords\General;

use BasilLangevin\LaravelDataJsonSchemas\Enums\Format;
use BasilLangevin\LaravelDataJsonSchemas\Exceptions\SchemaConfigurationException;
use BasilLangevin\LaravelDataJsonSchemas\Keywords\Contracts\HandlesMultipleInstances;
use BasilLangevin\LaravelDataJsonSchemas\Keywords\Keyword;
use Illuminate\Support\Collection;

class FormatKeyword extends Keyword implements HandlesMultipleInstances
{
    public function __construct(protected string|Format $value) {}

    /**
     * {@inheritdoc}
     */
    public function get(): string|Format
    {
        return $this->value;
    }

    /**
     * {@inheritdoc}
     */
    public function apply(Collection $schema): Collection
    {
        return $schema->merge([
            'format' => $this->value instanceof Format
                ? $this->value->value
                : $this->value,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public static function applyMultiple(Collection $schema, Collection $instances): Collection
    {
        if ($instances->map->get()->unique()->count() > 1) {
            throw new SchemaConfigurationException('A schema cannot have more than one format.');
        }

        /** @var FormatKeyword $instance */
        $instance = $instances->first();

        return $instance->apply($schema);
    }
}
