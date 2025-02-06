<?php

namespace BasilLangevin\LaravelDataSchemas\Annotators;

use BasilLangevin\LaravelDataSchemas\Annotators\Contracts\AnnotatesSchema;
use BasilLangevin\LaravelDataSchemas\Attributes\CustomAnnotation;
use BasilLangevin\LaravelDataSchemas\Schemas\Contracts\Schema;
use BasilLangevin\LaravelDataSchemas\Support\Contracts\EntityWrapper;

class CustomAnnotationAnnotator implements AnnotatesSchema
{
    /**
     * Add any custom annotations defined by CustomAnnotation attributes to the Schema.
     */
    public static function annotateSchema(Schema $schema, EntityWrapper $entity): Schema
    {
        /** @var array<string, string> $annotations */
        $annotations = $entity->attributes(CustomAnnotation::class)
            ->flatMap->getValue()
            ->toArray();

        if (empty($annotations)) {
            return $schema;
        }

        return $schema->customAnnotation($annotations);
    }
}
