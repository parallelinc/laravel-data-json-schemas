<?php

namespace BasilLangevin\LaravelDataJsonSchemas\Actions;

use BasilLangevin\LaravelDataJsonSchemas\Actions\Concerns\Runnable;
use BasilLangevin\LaravelDataJsonSchemas\Schemas\ObjectSchema;

class DisallowAdditionalProperties
{
    /** @use Runnable<array{ObjectSchema}, ObjectSchema> */
    use Runnable;

    /**
     * Set the Schema's "additionalProperties" keyword to false.
     */
    public function handle(ObjectSchema $schema): ObjectSchema
    {
        return $schema->additionalProperties(false);
    }
}
