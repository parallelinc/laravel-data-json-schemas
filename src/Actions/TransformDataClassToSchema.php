<?php

namespace BasilLangevin\LaravelDataSchemas\Actions;

use BasilLangevin\LaravelDataSchemas\Actions\Concerns\Runnable;
use BasilLangevin\LaravelDataSchemas\Enums\DataType;
use BasilLangevin\LaravelDataSchemas\Schemas\Contracts\Schema;
use BasilLangevin\LaravelDataSchemas\Schemas\ObjectSchema;
use BasilLangevin\LaravelDataSchemas\Support\ClassWrapper;
use BasilLangevin\LaravelDataSchemas\Support\SchemaTree;
use Spatie\LaravelData\Data;

class TransformDataClassToSchema
{
    /** @use Runnable<array{class-string<Data>, ?SchemaTree}, ObjectSchema> */
    use Runnable;

    /**
     * Dependency injection improves type inference.
     *
     * @param  ApplyAnnotationsToSchema<ObjectSchema>  $annotator
     * @param  ApplyRuleConfigurationsToSchema<ObjectSchema>  $ruleConfigurator
     */
    public function __construct(
        protected ApplyAnnotationsToSchema $annotator,
        protected ApplyRuleConfigurationsToSchema $ruleConfigurator,
    ) {}

    /**
     * Transform a data class to a Schema with the appropriate keywords.
     *
     * @param  class-string<Data>  $dataClass
     */
    public function handle(string $dataClass, ?SchemaTree $tree = null): ObjectSchema
    {
        $tree ??= app(SchemaTree::class)->rootClass($dataClass);

        $tree->incrementDataClassCount($dataClass);

        if ($tree->hasRegisteredSchema($dataClass)) {
            return $tree->getRegisteredSchema($dataClass);
        }

        $class = ClassWrapper::make($dataClass);
        $schema = ObjectSchema::make();
        $tree->registerSchema($dataClass, $schema);

        $schema->class($dataClass)
            ->type(DataType::Object)
            ->pipe(fn (ObjectSchema $schema) => $this->annotator->handle($schema, $class))
            ->pipe(fn (ObjectSchema $schema) => $this->ruleConfigurator->handle($schema, $class))
            ->pipe(fn (ObjectSchema $schema) => ApplyPropertiesToDataObjectSchema::run($schema, $class, $tree))
            ->pipe(fn (ObjectSchema $schema) => ApplyRequiredToDataObjectSchema::run($schema, $class))
            ->tree($tree);

        return $schema;
    }
}
