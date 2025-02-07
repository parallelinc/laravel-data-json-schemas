<?php

namespace BasilLangevin\LaravelDataJsonSchemas\Schemas\Contracts;

use Illuminate\Support\Collection;

interface AppliesKeywords
{
    /**
     * Set the value for the appropriate keyword.
     */
    public function setKeyword(string $name, mixed ...$arguments): self;

    /**
     * Get the value for the appropriate keyword.
     */
    public function getKeyword(string $name): mixed;

    /**
     * Add the definition for a keyword to the given schema.
     *
     * @param  Collection<string, mixed>  $schema
     * @return Collection<string, mixed>
     */
    public function applyKeyword(string $name, Collection $schema): Collection;

    /**
     * Check if the given keyword has been set.
     */
    public function hasKeyword(string $name): bool;

    /**
     * Allow keyword methods to be called on the schema type.
     */
    public function __call(mixed $name, mixed $arguments): mixed;
}
