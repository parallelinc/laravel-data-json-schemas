<?php

namespace BasilLangevin\LaravelDataJsonSchemas\Actions;

use BasilLangevin\LaravelDataJsonSchemas\Actions\Concerns\Runnable;
use BasilLangevin\LaravelDataJsonSchemas\Enums\Format;
use BasilLangevin\LaravelDataJsonSchemas\Schemas\StringSchema;
use BasilLangevin\LaravelDataJsonSchemas\Schemas\UnionSchema;

class ApplyDateTimeFormatToSchema
{
    /** @use Runnable<array{StringSchema|UnionSchema}, StringSchema|UnionSchema> */
    use Runnable;

    /**
     * Set the Schema's "format" keyword to "date-time".
     */
    public function handle(StringSchema|UnionSchema $schema): StringSchema|UnionSchema
    {
        return $schema->format(Format::DateTime);
    }
}
