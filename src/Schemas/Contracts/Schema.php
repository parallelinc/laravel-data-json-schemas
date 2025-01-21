<?php

namespace BasilLangevin\LaravelDataSchemas\Schemas\Contracts;

use BasilLangevin\LaravelDataSchemas\Support\Contracts\Pipeable;
use BasilLangevin\LaravelDataSchemas\Support\Contracts\Whenable;
use Illuminate\Contracts\Support\Arrayable;

/**
 * @property static array $keywords The keywords that are available for this schema type.
 *
 * @see https://json-schema.org/draft/2020-12/json-schema-validation
 */
interface Schema extends AppliesKeywords, Arrayable, Pipeable, Whenable
{
    public function __construct(string $name = '', string $description = '');

    public static function make(string $name = '', string $description = ''): self;

    public function name(string $name): self;

    public function getName(): string;

    public function cloneBaseStructure(): self;
}
