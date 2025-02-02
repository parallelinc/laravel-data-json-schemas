<?php

namespace BasilLangevin\LaravelDataSchemas\Attributes\Contracts;

interface ArrayAttribute
{
    /**
     * @return array<string, string>
     */
    public function getValue(): array;
}
