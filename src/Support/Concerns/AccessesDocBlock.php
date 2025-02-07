<?php

namespace BasilLangevin\LaravelDataJsonSchemas\Support\Concerns;

use BasilLangevin\LaravelDataJsonSchemas\Support\ClassWrapper;
use BasilLangevin\LaravelDataJsonSchemas\Support\DocBlockParser;
use Spatie\LaravelData\Data;

trait AccessesDocBlock
{
    public function getDocBlock(): ?DocBlockParser
    {
        return DocBlockParser::make($this->getDocComment());
    }

    public function getDocComment(): string|false
    {
        /** @var \ReflectionClass<Data>|\ReflectionProperty $reflector */
        $reflector = $this instanceof ClassWrapper ? $this->class : $this->property;

        return $reflector->getDocComment();
    }
}
