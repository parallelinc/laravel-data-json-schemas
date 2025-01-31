<?php

namespace BasilLangevin\LaravelDataSchemas\Schemas\Contracts;

use BasilLangevin\LaravelDataSchemas\Support\Contracts\Pipeable;
use BasilLangevin\LaravelDataSchemas\Support\Contracts\Whenable;

/**
 * @property static array $keywords The keywords that are available for this schema type.
 *
 * @see https://json-schema.org/draft/2020-12/json-schema-validation
 */
interface Schema extends AppliesKeywords, HasSchemaTree, Pipeable, Whenable
{
    public function __construct();

    public static function make(): self;

    public function cloneBaseStructure(): self;

    public function toArray(bool $nested = false): array;
}
