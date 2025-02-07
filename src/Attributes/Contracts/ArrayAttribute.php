<?php

namespace BasilLangevin\LaravelDataJsonSchemas\Attributes\Contracts;

interface ArrayAttribute
{
    /**
     * @return array<string, string>
     */
    public function getValue(): array;
}
