<?php

namespace BasilLangevin\LaravelDataSchemas\Support\Concerns;

use BasilLangevin\LaravelDataSchemas\Support\ClassWrapper;
use BasilLangevin\LaravelDataSchemas\Support\DocBlockParser;
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
