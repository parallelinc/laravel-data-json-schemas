<?php

namespace BasilLangevin\LaravelDataJsonSchemas\Annotators;

use BasilLangevin\LaravelDataJsonSchemas\Annotators\Contracts\AnnotatesSchema;
use BasilLangevin\LaravelDataJsonSchemas\Attributes\CustomAnnotation;
use BasilLangevin\LaravelDataJsonSchemas\Schemas\Contracts\Schema;
use BasilLangevin\LaravelDataJsonSchemas\Support\Contracts\EntityWrapper;

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
