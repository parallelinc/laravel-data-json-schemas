<?php

namespace BasilLangevin\LaravelDataSchemas\Annotators;

use BasilLangevin\LaravelDataSchemas\Annotators\Contracts\AnnotatesSchema;
use BasilLangevin\LaravelDataSchemas\Attributes\Title;
use BasilLangevin\LaravelDataSchemas\Schemas\Contracts\Schema;
use BasilLangevin\LaravelDataSchemas\Support\ClassWrapper;
use BasilLangevin\LaravelDataSchemas\Support\Contracts\EntityWrapper;
use Illuminate\Support\Stringable;

class TitleAnnotator implements AnnotatesSchema
{
    public static function annotateSchema(Schema $schema, EntityWrapper $entity): Schema
    {
        $title = self::getTitleAttribute($entity)
            ?? self::getTitleFromDocBlock($entity)
            ?? self::getTitleFromClass($entity);

        if (! $title) {
            return $schema;
        }

        return $schema->title($title);
    }

    /**
     * Get the title from the Title attribute if it is set.
     */
    protected static function getTitleAttribute(EntityWrapper $entity): ?string
    {
        if (! $entity->hasAttribute(Title::class)) {
            return null;
        }

        return $entity->getAttribute(Title::class)->getValue();
    }

    /**
     * Get the title from the doc block if it has a summary and description.
     */
    protected static function getTitleFromDocBlock(EntityWrapper $entity): ?string
    {
        if (! $docBlock = $entity->getDocBlock()) {
            return null;
        }

        // The doc block summary only becomes the title if it also has a description.
        if (! $docBlock->hasDescription()) {
            return null;
        }

        return $docBlock->getSummary();
    }

    /**
     * Get the title from the class name if it is a data object.
     */
    protected static function getTitleFromClass(EntityWrapper $entity): ?string
    {
        if (! $entity instanceof ClassWrapper) {
            return null;
        }

        return str($entity->getShortName())
            ->whenEndsWith('Data', fn (Stringable $string) => $string->beforeLast('Data'))
            ->snake()
            ->replace('_', ' ')
            ->title();
    }
}
