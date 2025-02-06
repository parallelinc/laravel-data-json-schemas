<?php

namespace BasilLangevin\LaravelDataSchemas\Keywords\General;

use BasilLangevin\LaravelDataSchemas\Enums\Format;
use BasilLangevin\LaravelDataSchemas\Exceptions\SchemaConfigurationException;
use BasilLangevin\LaravelDataSchemas\Keywords\Contracts\HandlesMultipleInstances;
use BasilLangevin\LaravelDataSchemas\Keywords\Keyword;
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
