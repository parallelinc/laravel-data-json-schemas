<?php

namespace BasilLangevin\LaravelDataJsonSchemas\Support\Contracts;

use Closure;

interface Whenable
{
    public function when(bool $condition, Closure $callback): static;
}
