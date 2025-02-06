<?php

namespace BasilLangevin\LaravelDataSchemas\Keywords\Contracts;

use BasilLangevin\LaravelDataSchemas\Schemas\Contracts\Schema;

/**
 * Implemented by keywords that need to know the parent schema.
 */
interface ReceivesParentSchema
{
    /**
     * Set the parent schema to the class that this keyword was applied to.
     */
    public function parentSchema(Schema $parentSchema): self;
}
