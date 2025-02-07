<?php

namespace BasilLangevin\LaravelDataJsonSchemas\Keywords\Object;

use BasilLangevin\LaravelDataJsonSchemas\Keywords\Contracts\HandlesMultipleInstances;
use BasilLangevin\LaravelDataJsonSchemas\Keywords\Keyword;
use BasilLangevin\LaravelDataJsonSchemas\Schemas\Contracts\Schema;
use Illuminate\Support\Collection;

class PropertiesKeyword extends Keyword implements HandlesMultipleInstances
{
    /**
     * @param  array<string, Schema>  $value
     */
    public function __construct(protected array $value) {}

    /**
     * {@inheritdoc}
     *
     * @return array<string, Schema>
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
        $properties = self::resolveProperties(
            collect($this->get())
        );

        return $schema->merge(compact('properties'));
    }

    /**
     * {@inheritdoc}
     */
    public static function applyMultiple(Collection $schema, Collection $instances): Collection
    {
        /** @var Collection<string, Schema> */
        $properties = $instances->flatMap->get()->unique(fn ($property, $name) => $name);

        $properties = self::resolveProperties($properties);

        return $schema->merge(compact('properties'));
    }

    /**
     * Transform each property into its JSON Schema array representation.
     *
     * @param  Collection<string, Schema>  $properties
     * @return array<string, array<string, mixed>>
     */
    protected static function resolveProperties(Collection $properties): array
    {
        return $properties->map(fn (Schema $property) => $property->toArray(nested: true))->all();
    }
}
