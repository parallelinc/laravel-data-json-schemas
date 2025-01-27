<?php

namespace BasilLangevin\LaravelDataSchemas\Schemas;

use BadMethodCallException;
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

    public function applyType(PropertyWrapper $property): self
    {
        $this->constituentSchemas = $property->getTypes()
            ->map(function (ReflectionNamedType $type) {
                $action = new MakeSchemaForReflectionType(unionNullableTypes: false);

                return $action->handle($type);
            });

        $includesNull = $this->constituentSchemas->contains(fn (SingleTypeSchema $schema) => $schema instanceof NullSchema);

        if ($property->isNullable() && ! $includesNull) {
            $this->constituentSchemas->push(NullSchema::make());
        }

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
