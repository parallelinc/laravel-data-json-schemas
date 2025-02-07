<?php

namespace BasilLangevin\LaravelDataJsonSchemas\Schemas\Contracts;

use BasilLangevin\LaravelDataJsonSchemas\Enums\DataType;

interface HasDataType
{
    public static function getDataType(): DataType;

    public function applyType(): static;
}
