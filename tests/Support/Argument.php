<?php

namespace BasilLangevin\LaravelDataJsonSchemas\Tests\Support;

class Argument
{
    public function __construct(public mixed $value) {}

    public function getValue(): mixed
    {
        return $this->value;
    }
}
