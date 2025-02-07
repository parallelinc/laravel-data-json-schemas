<?php

namespace BasilLangevin\LaravelDataJsonSchemas\Support\Concerns;

use Closure;

trait PipeCallbacks
{
    /**
     * Pipe the callback to the object.
     *
     * @template TReturn The type of the value returned by the callback.
     *
     * @param  Closure(static): TReturn  $callback
     * @return TReturn
     */
    public function pipe(Closure $callback): mixed
    {
        return $callback($this);
    }
}
