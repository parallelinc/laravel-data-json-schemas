<?php

namespace BasilLangevin\LaravelDataJsonSchemas\Keywords\Composition;

use BasilLangevin\LaravelDataJsonSchemas\Keywords\Contracts\MergesMultipleInstancesIntoAllOf;
use BasilLangevin\LaravelDataJsonSchemas\Keywords\Contracts\ReceivesParentSchema;
use BasilLangevin\LaravelDataJsonSchemas\Keywords\Keyword;
use BasilLangevin\LaravelDataJsonSchemas\Schemas\Contracts\Schema;
use Closure;
use Illuminate\Support\Collection;

class NotKeyword extends Keyword implements MergesMultipleInstancesIntoAllOf, ReceivesParentSchema
{
    protected Schema $parentSchema;

    public function __construct(protected Closure $callback) {}

    /**
     * {@inheritdoc}
     */
    public function parentSchema(Schema $parentSchema): self
    {
        $this->parentSchema = $parentSchema;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function get(): Closure
    {
        return $this->callback;
    }

    /**
     * {@inheritdoc}
     */
    public function apply(Collection $schema): Collection
    {
        $notSchema = $this->parentSchema->cloneBaseStructure();

        ($this->callback)($notSchema);

        return $schema->merge(['not' => $notSchema->toArray(true)]);
    }
}
