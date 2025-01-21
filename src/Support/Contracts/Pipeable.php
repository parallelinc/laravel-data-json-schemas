<?php

namespace BasilLangevin\LaravelDataSchemas\Support\Contracts;

interface Pipeable
{
    public function pipe(callable $callback);
}
