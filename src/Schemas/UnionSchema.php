<?php

namespace BasilLangevin\LaravelDataSchemas\Schemas;

use BadMethodCallException;
use BasilLangevin\LaravelDataSchemas\Actions\MakeSchemaForReflectionType;
use BasilLangevin\LaravelDataSchemas\Actions\TransformDataClassToSchema;
use BasilLangevin\LaravelDataSchemas\Keywords\Keyword;
use BasilLangevin\LaravelDataSchemas\Schemas\Concerns\ConstructsSchema;
use BasilLangevin\LaravelDataSchemas\Schemas\Concerns\HasKeywords;
use BasilLangevin\LaravelDataSchemas\Schemas\Contracts\Schema;
use BasilLangevin\LaravelDataSchemas\Schemas\Contracts\SingleTypeSchema;
use BasilLangevin\LaravelDataSchemas\Support\Concerns\PipeCallbacks;
use BasilLangevin\LaravelDataSchemas\Support\Concerns\WhenCallbacks;
use BasilLangevin\LaravelDataSchemas\Support\PropertyWrapper;
use BasilLangevin\LaravelDataSchemas\Support\SchemaTree;
use Illuminate\Support\Collection;
use ReflectionNamedType;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Types\Type;

class UnionSchema implements Schema
{
    use ConstructsSchema;
    use HasKeywords {
        __call as __callUnionKeyword;
    }
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

    public function applyType(PropertyWrapper $property, SchemaTree $tree): self
    {
        $this->constituentSchemas = $property->getReflectionTypes()
            ->map(fn (ReflectionNamedType $type) => $this->makeConstituentSchema($type, $tree));

        $includesNull = $this->constituentSchemas->contains(fn (SingleTypeSchema $schema) => $schema instanceof NullSchema);

        if ($property->isNullable() && ! $includesNull) {
            $this->constituentSchemas->push(NullSchema::make());
        }

        if (! $this->canBeConsolidated()) {
            $this->constituentSchemas->each->applyType();
        }

        return $this;
    }

    protected function makeConstituentSchema(ReflectionNamedType $type, SchemaTree $tree): SingleTypeSchema
    {
        if (is_subclass_of($type->getName(), Data::class)) {
            return TransformDataClassToSchema::run($type->getName(), $tree);
        }

        $action = new MakeSchemaForReflectionType(unionNullableTypes: false);

        return $action->handle($type);
    }

    public function tree(SchemaTree $tree): self
    {
        $this->getConstituentSchemas()->each->tree($tree);

        return $this;
    }

    /**
     * Allow keyword methods to be called on the schema type.
     */
    public function __call($name, $arguments)
    {
        try {
            return $this->__callUnionKeyword($name, $arguments);
        } catch (\BadMethodCallException $e) {
        }

        $badCalls = 0;

        $results = $this->getConstituentSchemas()
            ->map(function (SingleTypeSchema $schema) use ($name, $arguments, &$badCalls) {
                try {
                    return $schema->__call($name, $arguments);
                } catch (\BadMethodCallException $e) {
                    $badCalls++;

                    return $e;
                }
            })
            ->reject(fn ($result) => $result instanceof \BadMethodCallException);

        if ($badCalls === $this->getConstituentSchemas()->count()) {
            throw new BadMethodCallException("Method \"{$name}\" not found");
        }

        if ($results->every(fn ($result) => $result instanceof Schema)) {
            return $this;
        }

        if ($results->count() === 1) {
            return $results->first();
        }

        return $results;
    }

    public function cloneBaseStructure(): self
    {
        $clone = new static;

        $clone->constituentSchemas = $this->constituentSchemas
            ->map(fn (SingleTypeSchema $schema) => $schema->cloneBaseStructure());

        return $clone;
    }

    protected function canBeConsolidated(): bool
    {
        return $this->getConstituentSchemas()
            ->doesntContain(fn (SingleTypeSchema $schema) => $schema instanceof ObjectSchema);
    }

    protected function buildConsolidatedSchema(): array
    {
        $types = $this->getConstituentSchemas()
            ->map(fn (SingleTypeSchema $schema) => $schema::getDataType())
            ->map->value
            ->toArray();

        $constituentSchemas = $this->getConstituentSchemas()
            ->flatMap->toArray(true);

        return [
            ...$this->buildSchema(),
            'type' => $types,
            ...$constituentSchemas,
        ];
    }

    protected function buildAnyOfSchema(): array
    {
        $constituentSchemas = $this->getConstituentSchemas()
            ->map->toArray(true)
            ->toArray();

        return [
            ...$this->buildSchema(),
            'anyOf' => $constituentSchemas,
        ];
    }

    /**
     * Convert the schema to an array.
     */
    public function toArray(bool $nested = false): array
    {
        return $this->canBeConsolidated()
            ? $this->buildConsolidatedSchema()
            : $this->buildAnyOfSchema();
    }
}
