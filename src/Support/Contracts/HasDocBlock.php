<?php

namespace BasilLangevin\LaravelDataJsonSchemas\Support\Contracts;

use BasilLangevin\LaravelDataJsonSchemas\Support\DocBlockParser;

interface HasDocBlock
{
    public function getDocBlock(): ?DocBlockParser;
}
