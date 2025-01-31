<?php

namespace BasilLangevin\LaravelDataSchemas\Schemas\Contracts;

use BasilLangevin\LaravelDataSchemas\Support\SchemaTree;

interface HasSchemaTree
{
    public function tree(SchemaTree $tree): self;
}
