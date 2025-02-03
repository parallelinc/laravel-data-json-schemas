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
    /** @use Runnable<ObjectSchema> */
    use Runnable;

    /**
     * Transform a data class to a schema.
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
            ->pipe(fn (Schema $schema) => ApplyAnnotationsToSchema::run($schema, $class))
            ->pipe(fn (Schema $schema) => ApplyRuleConfigurationsToSchema::run($schema, $class))
            ->pipe(fn (Schema $schema) => ApplyPropertiesToDataObjectSchema::run($schema, $class, $tree))
            ->pipe(fn (Schema $schema) => ApplyRequiredToDataObjectSchema::run($schema, $class))
            ->tree($tree);

        return $schema;
    }
}
