<?php

namespace BasilLangevin\LaravelDataJsonSchemas\Actions;

use BasilLangevin\LaravelDataJsonSchemas\Actions\Concerns\Runnable;
use BasilLangevin\LaravelDataJsonSchemas\Annotators\Contracts\AnnotatesSchema;
use BasilLangevin\LaravelDataJsonSchemas\Annotators\CustomAnnotationAnnotator;
use BasilLangevin\LaravelDataJsonSchemas\Annotators\DefaultAnnotationAnnotator;
use BasilLangevin\LaravelDataJsonSchemas\Annotators\DescriptionAnnotator;
use BasilLangevin\LaravelDataJsonSchemas\Annotators\TitleAnnotator;
use BasilLangevin\LaravelDataJsonSchemas\Schemas\Contracts\Schema;
use BasilLangevin\LaravelDataJsonSchemas\Support\Contracts\EntityWrapper;

/**
 * @template TSchema of Schema
 */
class ApplyAnnotationsToSchema
{
    /** @use Runnable<array{TSchema, EntityWrapper}, TSchema> */
    use Runnable;

    /** @var array<class-string<AnnotatesSchema>> */
    protected static array $annotators = [
        TitleAnnotator::class,
        DescriptionAnnotator::class,
        CustomAnnotationAnnotator::class,
        DefaultAnnotationAnnotator::class,
    ];

    /**
     * Apply each applicable annotation keyword to the Schema.
     *
     * @param  TSchema  $schema
     * @return TSchema
     */
    public function handle(Schema $schema, EntityWrapper $entity): Schema
    {
        foreach (self::$annotators as $annotator) {
            $annotator::annotateSchema($schema, $entity);
        }

        return $schema;
    }
}
