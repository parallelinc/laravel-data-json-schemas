<?php

namespace BasilLangevin\LaravelDataSchemas\Actions;

use BasilLangevin\LaravelDataSchemas\Actions\Concerns\Runnable;
use BasilLangevin\LaravelDataSchemas\Annotators\Contracts\AnnotatesSchema;
use BasilLangevin\LaravelDataSchemas\Annotators\CustomAnnotationAnnotator;
use BasilLangevin\LaravelDataSchemas\Annotators\DefaultAnnotationAnnotator;
use BasilLangevin\LaravelDataSchemas\Annotators\DescriptionAnnotator;
use BasilLangevin\LaravelDataSchemas\Annotators\TitleAnnotator;
use BasilLangevin\LaravelDataSchemas\Schemas\Contracts\Schema;
use BasilLangevin\LaravelDataSchemas\Support\Contracts\EntityWrapper;

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
