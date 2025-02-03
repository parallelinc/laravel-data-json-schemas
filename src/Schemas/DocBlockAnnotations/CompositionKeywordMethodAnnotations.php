<?php

namespace BasilLangevin\LaravelDataSchemas\Schemas\DocBlockAnnotations;

use Closure;
use Illuminate\Support\Collection;

/**
 * @method static not(Closure $callback) Set the not keyword of the schema.
 * @method Closure(Schema $schema): void|Collection<int, Closure(Schema $schema): void> getNot() Get the value(s) passed to the not method.
 *
 * @see BasilLangevin\LaravelDataSchemas\Keywords\Composition\NotKeyword
 */
trait CompositionKeywordMethodAnnotations {}
