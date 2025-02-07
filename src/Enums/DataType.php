<?php

namespace BasilLangevin\LaravelDataJsonSchemas\Enums;

/**
 * The types that can be used in a JSON Schema's "type" keyword.
 */
enum DataType: string
{
    case String = 'string';
    case Number = 'number';
    case Integer = 'integer';
    case Boolean = 'boolean';
    case Null = 'null';
    case Object = 'object';
    case Array = 'array';
}
