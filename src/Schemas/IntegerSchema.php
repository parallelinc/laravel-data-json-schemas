<?php

namespace BasilLangevin\LaravelDataJsonSchemas\Schemas;

use BasilLangevin\LaravelDataJsonSchemas\Enums\DataType;

class IntegerSchema extends NumberSchema
{
    public static DataType $type = DataType::Integer;
}
