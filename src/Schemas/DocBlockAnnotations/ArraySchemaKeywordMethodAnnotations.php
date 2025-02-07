<?php

namespace BasilLangevin\LaravelDataJsonSchemas\Schemas\DocBlockAnnotations;

use BasilLangevin\LaravelDataJsonSchemas\Schemas\Contracts\Schema;
use Illuminate\Support\Collection;

/**
 * @method static maxItems(int $maxItems) Set the maxItems keyword of the schema.
 * @method int|Collection<int, int> getMaxItems() Get the value(s) passed to the maxItems method.
 *
 * @see BasilLangevin\LaravelDataJsonSchemas\Keywords\Array\MaxItemsKeyword
 *
 * @method static minItems(int $minItems) Set the minItems keyword of the schema.
 * @method int|Collection<int, int> getMinItems() Get the value(s) passed to the minItems method.
 *
 * @see BasilLangevin\LaravelDataJsonSchemas\Keywords\Array\MinItemsKeyword
 *
 * @method static items(Schema $schema) Set the items keyword of the schema.
 * @method Schema|Collection<int, Schema> getItems() Get the value(s) passed to the items method.
 *
 * @see BasilLangevin\LaravelDataJsonSchemas\Keywords\Array\ItemsKeyword
 */
trait ArraySchemaKeywordMethodAnnotations {}
