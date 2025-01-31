<?php

namespace BasilLangevin\LaravelDataSchemas\Schemas\DocBlockAnnotations;

/**
 * @method static maxItems(int $maxItems) Set the maxItems keyword of the schema.
 * @method int|Collection<int, int> getMaxItems() Get the value(s) passed to the maxItems method.
 *
 * @see BasilLangevin\LaravelDataSchemas\Keywords\Array\MaxItemsKeyword
 *
 * @method static minItems(int $minItems) Set the minItems keyword of the schema.
 * @method int|Collection<int, int> getMinItems() Get the value(s) passed to the minItems method.
 *
 * @see BasilLangevin\LaravelDataSchemas\Keywords\Array\MinItemsKeyword
 *
 * @method static items(Schema $schema) Set the items keyword of the schema.
 * @method Schema|Collection<int, Schema> getItems() Get the value(s) passed to the items method.
 *
 * @see BasilLangevin\LaravelDataSchemas\Keywords\Array\ItemsKeyword
 */
trait ArraySchemaKeywordMethodAnnotations {}
