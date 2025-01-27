<?php

namespace BasilLangevin\LaravelDataSchemas\Annotators;

use Illuminate\Support\Stringable;
use BasilLangevin\LaravelDataSchemas\Attributes\Title;
use BasilLangevin\LaravelDataSchemas\Support\ClassWrapper;
use BasilLangevin\LaravelDataSchemas\Support\PropertyWrapper;
use BasilLangevin\LaravelDataSchemas\Schemas\Contracts\Schema;
use BasilLangevin\LaravelDataSchemas\Support\Contracts\EntityWrapper;
use BasilLangevin\LaravelDataSchemas\Annotators\Contracts\AnnotatesSchema;

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
    protected static function getTitleFromClass(ClassWrapper|PropertyWrapper $entity): ?string
    {
        if (! $entity->isDataObject()) {
            return null;
        }

        if ($entity instanceof PropertyWrapper) {
            $entity = $entity->getDataClass();
        }

        return str($entity->getShortName())
            ->whenEndsWith('Data', fn (Stringable $string) => $string->beforeLast('Data'))
            ->snake()
            ->replace('_', ' ')
            ->title();
    }
}
