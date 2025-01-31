<?php

namespace BasilLangevin\LaravelDataSchemas\Schemas\DocBlockAnnotations;

/**
 * @method static type(DataType $type) Set the type keyword of the schema.
 * @method DataType|Collection<int, DataType> getType() Get the value(s) passed to the type method.
 *
 * @see BasilLangevin\LaravelDataSchemas\Keywords\General\TypeKeyword
 *
 * @method static enum(string|array $enum) Set the enum keyword of the schema.
 * @method string|array|Collection<int, string|array> getEnum() Get the value(s) passed to the enum method.
 *
 * @see BasilLangevin\LaravelDataSchemas\Keywords\General\EnumKeyword
 *
 * @method static const(mixed $const) Set the const keyword of the schema.
 * @method mixed|Collection<int, mixed> getConst() Get the value(s) passed to the const method.
 *
 * @see BasilLangevin\LaravelDataSchemas\Keywords\General\ConstKeyword
 *
 * @method static format(string|Format $format) Set the format keyword of the schema.
 * @method string|Format|Collection<int, string|Format> getFormat() Get the value(s) passed to the format method.
 *
 * @see BasilLangevin\LaravelDataSchemas\Keywords\General\FormatKeyword
 */
trait GeneralKeywordMethodAnnotations {}
