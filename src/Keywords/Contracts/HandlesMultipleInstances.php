<?php

namespace BasilLangevin\LaravelDataSchemas\Keywords\Contracts;

use Illuminate\Support\Collection;

/**
 * Implemented by keywords that can apply multiple instances to a schema.
 */
interface HandlesMultipleInstances
{
    /**
     * Apply multiple instances of the keyword to the schema.
     *
     * @param  Collection<string, mixed>  $schema
     * @param  Collection<int, static>  $instances
     * @return Collection<string, mixed>
     */
    public static function applyMultiple(Collection $schema, Collection $instances): Collection;
}
