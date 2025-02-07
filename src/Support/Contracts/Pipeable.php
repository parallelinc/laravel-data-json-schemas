<?php

namespace BasilLangevin\LaravelDataJsonSchemas\Support\Contracts;

use Closure;

interface Pipeable
{
    /**
     * Pipe the callback to the object.
     *
     * @template TReturn The type of the value returned by the callback.
     *
     * @param  Closure(static): TReturn  $callback
     * @return TReturn
     */
    public function pipe(Closure $callback): mixed;
}
