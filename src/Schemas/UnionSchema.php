<?php

namespace BasilLangevin\LaravelDataSchemas\Schemas;

use BasilLangevin\LaravelDataSchemas\Actions\MakeSchemaForReflectionType;
use BasilLangevin\LaravelDataSchemas\Keywords\Keyword;
use BasilLangevin\LaravelDataSchemas\Schemas\Concerns\ConstructsSchema;
use BasilLangevin\LaravelDataSchemas\Schemas\Concerns\HasKeywords;
use BasilLangevin\LaravelDataSchemas\Schemas\Contracts\Schema;
use BasilLangevin\LaravelDataSchemas\Schemas\Contracts\SingleTypeSchema;
use BasilLangevin\LaravelDataSchemas\Support\Concerns\PipeCallbacks;
use BasilLangevin\LaravelDataSchemas\Support\Concerns\WhenCallbacks;
use BasilLangevin\LaravelDataSchemas\Support\PropertyWrapper;
use Illuminate\Support\Collection;
use ReflectionNamedType;

class UnionSchema implements Schema
{
    use ConstructsSchema;
    use HasKeywords;
    use PipeCallbacks;
    use WhenCallbacks;

    public static array $keywords = [
        Keyword::ANNOTATION_KEYWORDS,
    ];

    protected Collection $constituentSchemas;

    public function getConstituentSchemas(): Collection
    {
        return $this->constituentSchemas;
    }

    public function applyType(PropertyWrapper $property): self
    {
        $this->constituentSchemas = $property->getTypes()
            ->map(function (ReflectionNamedType $type) {
                return MakeSchemaForReflectionType::run($type);
            });

        return $this;
    }

    public function cloneBaseStructure(): self
    {
        $clone = new static;

        $clone->constituentSchemas = $this->constituentSchemas
            ->map(fn (SingleTypeSchema $schema) => $schema->cloneBaseStructure());

        return $clone;
    }

    /**
     * Convert the schema to an array.
     */
    public function toArray(): array
    {
        $types = $this->getConstituentSchemas()
            ->map(fn (SingleTypeSchema $schema) => $schema::getDataType())
            ->map->value
            ->toArray();

        $constituentSchemas = $this->getConstituentSchemas()
            ->flatMap->buildSchema();

        return [
            'type' => $types,
            ...$this->buildSchema(),
            ...$constituentSchemas,
        ];
    }
}
