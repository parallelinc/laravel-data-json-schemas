<?php

namespace BasilLangevin\LaravelDataSchemas\Actions;

use BasilLangevin\LaravelDataSchemas\Actions\Concerns\Runnable;
use BasilLangevin\LaravelDataSchemas\Schemas\ObjectSchema;
use BasilLangevin\LaravelDataSchemas\Support\ClassWrapper;
use BasilLangevin\LaravelDataSchemas\Support\SchemaTree;

class TransformDataClassToSchema
{
    use Runnable;

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

        $schema->class($class->getName())
            ->type('object')
            ->pipe(fn (ObjectSchema $schema) => ApplyAnnotationsToSchema::run($schema, $class))
            ->pipe(fn (ObjectSchema $schema) => ApplyRuleConfigurationsToSchema::run($schema, $class))
            ->pipe(fn (ObjectSchema $schema) => ApplyPropertiesToDataObjectSchema::run($schema, $class, $tree))
            ->pipe(fn (ObjectSchema $schema) => ApplyRequiredToDataObjectSchema::run($schema, $class))
            ->tree($tree);

        return $schema;
    }
}
