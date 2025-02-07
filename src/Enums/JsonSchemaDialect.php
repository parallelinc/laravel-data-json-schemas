<?php

namespace BasilLangevin\LaravelDataJsonSchemas\Enums;

/**
 * The available JSON Schema dialects.
 */
enum JsonSchemaDialect: string
{
    case Draft4 = 'http://json-schema.org/draft-04/schema#';
    case Draft6 = 'http://json-schema.org/draft-06/schema#';
    case Draft7 = 'http://json-schema.org/draft-07/schema#';
    case Draft201909 = 'https://json-schema.org/draft/2019-09/schema';
    case Draft202012 = 'https://json-schema.org/draft/2020-12/schema';
}
