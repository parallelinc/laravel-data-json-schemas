<?php

namespace BasilLangevin\LaravelDataSchemas\Support\Contracts;

interface Whenable
{
    public function when(bool $condition, callable $callback): self;
}
