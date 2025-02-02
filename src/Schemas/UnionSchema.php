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
use BasilLangevin\LaravelDataSchemas\Schemas\DocBlockAnnotations\AnnotationKeywordMethodAnnotations;
use BasilLangevin\LaravelDataSchemas\Schemas\DocBlockAnnotations\ArraySchemaKeywordMethodAnnotations;
use BasilLangevin\LaravelDataSchemas\Schemas\DocBlockAnnotations\CompositionKeywordMethodAnnotations;
use BasilLangevin\LaravelDataSchemas\Schemas\DocBlockAnnotations\GeneralKeywordMethodAnnotations;
use BasilLangevin\LaravelDataSchemas\Schemas\DocBlockAnnotations\NumberSchemaKeywordMethodAnnotations;
use BasilLangevin\LaravelDataSchemas\Schemas\DocBlockAnnotations\ObjectSchemaKeywordMethodAnnotations;
use BasilLangevin\LaravelDataSchemas\Schemas\DocBlockAnnotations\StringSchemaKeywordMethodAnnotations;
use BasilLangevin\LaravelDataSchemas\Support\Concerns\PipeCallbacks;
use BasilLangevin\LaravelDataSchemas\Support\Concerns\WhenCallbacks;
use BasilLangevin\LaravelDataSchemas\Support\PropertyWrapper;
use BasilLangevin\LaravelDataSchemas\Support\SchemaTree;
use Illuminate\Support\Collection;
use ReflectionNamedType;
use Spatie\LaravelData\Data;

class UnionSchema implements Schema
{
    // General DocBlock annotations
    use AnnotationKeywordMethodAnnotations;

    // Constituent schemas DocBlock annotations
    use ArraySchemaKeywordMethodAnnotations;
    use CompositionKeywordMethodAnnotations;
    use ConstructsSchema;
    use GeneralKeywordMethodAnnotations;

    // Traits
    use HasKeywords {
        __call as __callUnionKeyword;
    }
    use NumberSchemaKeywordMethodAnnotations;
    use ObjectSchemaKeywordMethodAnnotations;
    use PipeCallbacks;
    use StringSchemaKeywordMethodAnnotations;
    use WhenCallbacks;

    /**
     * @var array<class-string<Keyword>|array<class-string<Keyword>>>
     */
    public static array $keywords = [
        Keyword::ANNOTATION_KEYWORDS,
    ];

    /**
     * The constituent schemas of the union.
     *
     * @var Collection<int, SingleTypeSchema>
     */
    protected Collection $constituentSchemas;

    /**
     * Get the constituent schemas of the union.
     *
     * @return Collection<int, SingleTypeSchema>
     */
    public function getConstituentSchemas(): Collection
    {
        return $this->constituentSchemas;
    }

    public function buildConstituentSchemas(PropertyWrapper $property, SchemaTree $tree): static
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

        /** @var SingleTypeSchema $schema */
        $schema = $action->handle($type);

        return $schema;
    }

    public function tree(SchemaTree $tree): static
    {
        $this->getConstituentSchemas()->each->tree($tree);

        return $this;
    }

    /**
     * Allow keyword methods to be called on the schema type.
     */
    public function __call(mixed $name, mixed $arguments): mixed
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

    public function cloneBaseStructure(): static
    {
        $clone = new static;

        $clone->constituentSchemas = $this->constituentSchemas
            ->map(fn (SingleTypeSchema $schema) => $schema->cloneBaseStructure());

        return $clone;
    }

    /**
     * Check if the constituent schemas can be consolidated into a single schema.
     */
    protected function canBeConsolidated(): bool
    {
        return $this->getConstituentSchemas()
            ->doesntContain(fn (SingleTypeSchema $schema) => $schema instanceof ObjectSchema);
    }

    /**
     * Consolidate the constituent schemas into a single schema.
     *
     * @return array<string, mixed>
     */
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

    /**
     * Consolidate the constituent schemas into an anyOf schema.
     *
     * @return array<string, mixed>
     */
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
