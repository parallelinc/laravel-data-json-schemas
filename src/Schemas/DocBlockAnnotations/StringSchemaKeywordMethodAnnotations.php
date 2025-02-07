<?php

namespace BasilLangevin\LaravelDataJsonSchemas\Schemas\DocBlockAnnotations;

use Illuminate\Support\Collection;

/**
 * @method static maxLength(int $maxLength) Set the maxLength keyword of the schema.
 * @method int|Collection<int, int> getMaxLength() Get the value(s) passed to the maxLength method.
 *
 * @see BasilLangevin\LaravelDataJsonSchemas\Keywords\String\MaxLengthKeyword
 *
 * @method static minLength(int $minLength) Set the minLength keyword of the schema.
 * @method int|Collection<int, int> getMinLength() Get the value(s) passed to the minLength method.
 *
 * @see BasilLangevin\LaravelDataJsonSchemas\Keywords\String\MinLengthKeyword
 *
 * @method static pattern(string $pattern) Set the pattern keyword of the schema.
 * @method string|Collection<int, string> getPattern() Get the value(s) passed to the pattern method.
 *
 * @see BasilLangevin\LaravelDataJsonSchemas\Keywords\String\PatternKeyword
 */
trait StringSchemaKeywordMethodAnnotations {}
