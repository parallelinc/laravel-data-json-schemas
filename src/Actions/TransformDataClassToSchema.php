<?php

namespace BasilLangevin\LaravelDataJsonSchemas\Actions;

use BasilLangevin\LaravelDataJsonSchemas\Actions\Concerns\Runnable;
use BasilLangevin\LaravelDataJsonSchemas\Enums\DataType;
use BasilLangevin\LaravelDataJsonSchemas\Schemas\Contracts\Schema;
use BasilLangevin\LaravelDataJsonSchemas\Schemas\ObjectSchema;
use BasilLangevin\LaravelDataJsonSchemas\Support\ClassWrapper;
use BasilLangevin\LaravelDataJsonSchemas\Support\SchemaTree;
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
            ->pipe(fn (ObjectSchema $schema) => DisallowAdditionalProperties::run($schema))
            ->tree($tree);

        return $schema;
    }
}
