<?php

namespace BasilLangevin\LaravelDataJsonSchemas\Schemas\DocBlockAnnotations;

use BasilLangevin\LaravelDataJsonSchemas\Enums\DataType;
use BasilLangevin\LaravelDataJsonSchemas\Enums\Format;
use Illuminate\Support\Collection;

/**
 * @method static type(DataType $type) Set the type keyword of the schema.
 * @method DataType|Collection<int, DataType> getType() Get the value(s) passed to the type method.
 *
 * @see BasilLangevin\LaravelDataJsonSchemas\Keywords\General\TypeKeyword
 *
 * @method static enum(string|array<int, int|string|bool|\BackedEnum> $enum) Set the enum keyword of the schema.
 * @method string|array<int, int|string|bool|\BackedEnum>|Collection<int, string|array<int, int|string|bool|\BackedEnum>> getEnum() Get the value(s) passed to the enum method.
 *
 * @see BasilLangevin\LaravelDataJsonSchemas\Keywords\General\EnumKeyword
 *
 * @method static const(mixed $const) Set the const keyword of the schema.
 * @method mixed|Collection<int, mixed> getConst() Get the value(s) passed to the const method.
 *
 * @see BasilLangevin\LaravelDataJsonSchemas\Keywords\General\ConstKeyword
 *
 * @method static format(string|Format $format) Set the format keyword of the schema.
 * @method string|Format|Collection<int, string|Format> getFormat() Get the value(s) passed to the format method.
 *
 * @see BasilLangevin\LaravelDataJsonSchemas\Keywords\General\FormatKeyword
 */
trait GeneralKeywordMethodAnnotations {}
