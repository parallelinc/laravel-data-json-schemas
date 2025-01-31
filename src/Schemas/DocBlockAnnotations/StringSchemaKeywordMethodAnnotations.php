<?php

namespace BasilLangevin\LaravelDataSchemas\Schemas\DocBlockAnnotations;

/**
 * @method static maxLength(int $maxLength) Set the maxLength keyword of the schema.
 * @method int|Collection<int, int> getMaxLength() Get the value(s) passed to the maxLength method.
 *
 * @see BasilLangevin\LaravelDataSchemas\Keywords\String\MaxLengthKeyword
 *
 * @method static minLength(int $minLength) Set the minLength keyword of the schema.
 * @method int|Collection<int, int> getMinLength() Get the value(s) passed to the minLength method.
 *
 * @see BasilLangevin\LaravelDataSchemas\Keywords\String\MinLengthKeyword
 *
 * @method static pattern(string $pattern) Set the pattern keyword of the schema.
 * @method string|Collection<int, string> getPattern() Get the value(s) passed to the pattern method.
 *
 * @see BasilLangevin\LaravelDataSchemas\Keywords\String\PatternKeyword
 */
trait StringSchemaKeywordMethodAnnotations {}
