<?php

namespace BasilLangevin\LaravelDataJsonSchemas\Schemas\DocBlockAnnotations;

use BasilLangevin\LaravelDataJsonSchemas\Schemas\Contracts\Schema;
use Illuminate\Support\Collection;

/**
 * @method static properties(array<string, Schema> $properties) Set the properties keyword of the schema.
 * @method array<string, Schema>|Collection<int, array<string, Schema>> getProperties() Get the value(s) passed to the properties method.
 *
 * @see BasilLangevin\LaravelDataJsonSchemas\Keywords\Object\PropertiesKeyword
 *
 * @method static required(array<string> $required) Set the required keyword of the schema.
 * @method array<string>|Collection<int, array<string>> getRequired() Get the value(s) passed to the required method.
 *
 * @see BasilLangevin\LaravelDataJsonSchemas\Keywords\Object\RequiredKeyword
 *
 * @method static maxProperties(int $maxProperties) Set the maxProperties keyword of the schema.
 * @method int|Collection<int, int> getMaxProperties() Get the value(s) passed to the maxProperties method.
 *
 * @see BasilLangevin\LaravelDataJsonSchemas\Keywords\Object\MaxPropertiesKeyword
 *
 * @method static minProperties(int $minProperties) Set the minProperties keyword of the schema.
 * @method int|Collection<int, int> getMinProperties() Get the value(s) passed to the minProperties method.
 *
 * @see BasilLangevin\LaravelDataJsonSchemas\Keywords\Object\MinPropertiesKeyword
 */
trait ObjectSchemaKeywordMethodAnnotations {}
