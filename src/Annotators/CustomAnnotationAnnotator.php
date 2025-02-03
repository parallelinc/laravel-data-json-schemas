<?php

namespace BasilLangevin\LaravelDataSchemas\Annotators;

use BasilLangevin\LaravelDataSchemas\Annotators\Contracts\AnnotatesSchema;
use BasilLangevin\LaravelDataSchemas\Attributes\CustomAnnotation;
use BasilLangevin\LaravelDataSchemas\Schemas\Contracts\Schema;
use BasilLangevin\LaravelDataSchemas\Support\Contracts\EntityWrapper;

class CustomAnnotationAnnotator implements AnnotatesSchema
{
    public static function annotateSchema(Schema $schema, EntityWrapper $entity): Schema
    {
        /** @var \Illuminate\Support\Collection<int, array<string, string>> $annotations */
        $annotations = $entity->attributes(CustomAnnotation::class)
            ->flatMap->getValue();

        if ($annotations->isEmpty()) {
            return $schema;
        }

        return $schema->customAnnotation($annotations->toArray());
    }
}
