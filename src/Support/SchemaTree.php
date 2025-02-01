<?php

namespace BasilLangevin\LaravelDataSchemas\Support;

use BasilLangevin\LaravelDataSchemas\Schemas\ObjectSchema;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Support\Stringable;

class SchemaTree
{
    protected ?string $rootClass = null;

    protected array $registeredSchemas = [];

    protected array $dataClassCounts = [];

    public function rootClass(string $dataClass): self
    {
        $this->rootClass = $dataClass;

        return $this;
    }

    public function registerSchema(string $dataClass, ObjectSchema $schema): void
    {
        $this->registeredSchemas[$dataClass] = $schema;
    }

    public function getRegisteredSchema(string $dataClass): ObjectSchema
    {
        return $this->registeredSchemas[$dataClass];
    }

    public function hasRegisteredSchema(string $dataClass): bool
    {
        return isset($this->registeredSchemas[$dataClass]);
    }

    public function incrementDataClassCount(string $dataClass): void
    {
        if (! isset($this->dataClassCounts[$dataClass])) {
            $this->dataClassCounts[$dataClass] = 0;
        }

        $this->dataClassCounts[$dataClass]++;
    }

    public function getDataClassCount(string $dataClass): int
    {
        return $this->dataClassCounts[$dataClass] ?? 0;
    }

    public function hasMultiple(string $dataClass): bool
    {
        return $this->getDataClassCount($dataClass) > 1;
    }

    public function getRefNames(): array
    {
        $names = collect($this->dataClassCounts)->keys()
            ->mapWithKeys(fn (string $dataClass) => [$dataClass => str($dataClass)])
            ->map->whenExactly($this->rootClass, fn () => str('#'))
            ->map->afterLast('\\')
            ->map->whenEndsWith('Data', fn (Stringable $name) => $name->beforeLast('Data'))
            ->map->kebab()
            ->map->whenNotExactly('#', fn (Stringable $name) => $name->prepend('#/$defs/'))
            ->map->toString();

        /** @var Collection<string, string> $names */
        return $names->map(function (string $name, string $dataClass) use ($names) {
            $index = $names->keys()->search(fn (string $value) => $value === $dataClass);

            $duplicates = $names->values()->filter(fn (string $value) => $value === $name);

            if ($duplicates->count() === 1) {
                return $name;
            }

            $number = $duplicates->keys()->flip()->get($index) + 1;

            return $name.'-'.$number;
        })->toArray();
    }

    public function getRefName(string $dataClass): string
    {
        return $this->getRefNames()[$dataClass];
    }

    protected function getDefClasses(): Collection
    {
        return collect($this->dataClassCounts)
            ->filter(fn (int $count) => $count > 1)
            ->keys()
            ->filter(fn (string $dataClass) => $dataClass !== $this->rootClass);
    }

    public function hasDefs(): bool
    {
        return $this->getDefClasses()->isNotEmpty();
    }

    public function getDefs(): array
    {
        return $this->getDefClasses()
            ->mapWithKeys(fn (string $dataClass) => $this->getDef($dataClass))
            ->toArray();
    }

    protected function getDef(string $dataClass): array
    {
        $name = Str::after($this->getRefName($dataClass), '#/$defs/');

        return [$name => $this->getRegisteredSchema($dataClass)->buildSchema()];
    }
}
