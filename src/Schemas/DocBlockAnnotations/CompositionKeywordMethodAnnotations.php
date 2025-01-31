<?php

namespace BasilLangevin\LaravelDataSchemas\Schemas\DocBlockAnnotations;

/**
 * @method static not(Closure(Schema $schema): void $callback) Set the not keyword of the schema.
 * @method Closure(Schema $schema): void|Collection<int, Closure(Schema $schema): void> getNot() Get the value(s) passed to the not method.
 *
 * @see BasilLangevin\LaravelDataSchemas\Keywords\Composition\NotKeyword
 */
trait CompositionKeywordMethodAnnotations {}
