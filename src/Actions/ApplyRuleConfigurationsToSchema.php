<?php

namespace BasilLangevin\LaravelDataSchemas\Actions;

use BasilLangevin\LaravelDataSchemas\Actions\Concerns\Runnable;
use BasilLangevin\LaravelDataSchemas\RuleConfigurators\Contracts\ConfiguresAnySchema;
use BasilLangevin\LaravelDataSchemas\RuleConfigurators\Contracts\ConfiguresArraySchema;
use BasilLangevin\LaravelDataSchemas\RuleConfigurators\Contracts\ConfiguresBooleanSchema;
use BasilLangevin\LaravelDataSchemas\RuleConfigurators\Contracts\ConfiguresIntegerSchema;
use BasilLangevin\LaravelDataSchemas\RuleConfigurators\Contracts\ConfiguresNumberSchema;
use BasilLangevin\LaravelDataSchemas\RuleConfigurators\Contracts\ConfiguresObjectSchema;
use BasilLangevin\LaravelDataSchemas\RuleConfigurators\Contracts\ConfiguresSchema;
use BasilLangevin\LaravelDataSchemas\RuleConfigurators\Contracts\ConfiguresStringSchema;
use BasilLangevin\LaravelDataSchemas\Schemas\ArraySchema;
use BasilLangevin\LaravelDataSchemas\Schemas\BooleanSchema;
use BasilLangevin\LaravelDataSchemas\Schemas\Contracts\Schema;
use BasilLangevin\LaravelDataSchemas\Schemas\IntegerSchema;
use BasilLangevin\LaravelDataSchemas\Schemas\NumberSchema;
use BasilLangevin\LaravelDataSchemas\Schemas\ObjectSchema;
use BasilLangevin\LaravelDataSchemas\Schemas\StringSchema;
use BasilLangevin\LaravelDataSchemas\Schemas\UnionSchema;
use BasilLangevin\LaravelDataSchemas\Support\AttributeWrapper;
use BasilLangevin\LaravelDataSchemas\Support\Contracts\EntityWrapper;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use ReflectionClass;
use ReflectionMethod;

class ApplyRuleConfigurationsToSchema
{
    /** @use Runnable<Schema> */
    use Runnable;

    /** @var array<string, interface-string<ConfiguresSchema>> */
    protected static array $contracts = [
        '*' => ConfiguresAnySchema::class,
        'array' => ConfiguresArraySchema::class,
        'boolean' => ConfiguresBooleanSchema::class,
        'integer' => ConfiguresIntegerSchema::class,
        'number' => ConfiguresNumberSchema::class,
        'object' => ConfiguresObjectSchema::class,
        'string' => ConfiguresStringSchema::class,
    ];

    public function handle(Schema $schema, EntityWrapper $entity): Schema
    {
        if ($schema instanceof UnionSchema) {
            $schema->getConstituentSchemas()->each(fn (Schema $schema) => $this->handle($schema, $entity));

            return $schema;
        }

        $attributes = $this->getConfigurableAttributes($entity);

        foreach ($attributes as $attribute) {
            $this->applyConfigurations($schema, $entity, $attribute);
        }

        return $schema;
    }

    /**
     * Call each applicable configure method on the Attribute's RuleConfigurator.
     */
    protected function applyConfigurations(Schema $schema, EntityWrapper $entity, AttributeWrapper $attribute): void
    {
        $configurator = $attribute->getRuleConfigurator();

        $this->getApplicableContracts($schema)
            ->intersect(class_implements($configurator) ?: []) // @phpstan-ignore-line
            ->flatMap(fn (string $contract) => $this->getContractMethods($contract))
            ->unique()
            ->each(fn (string $method) => $configurator::{$method}($schema, $entity, $attribute));
    }

    /**
     * Get the attributes that have a RuleConfigurator.
     *
     * @return Collection<int, AttributeWrapper>
     */
    protected function getConfigurableAttributes(EntityWrapper $entity): Collection
    {
        return collect($entity->attributes())
            ->filter->isValidationAttribute()
            ->filter->hasRuleConfigurator();
    }

    /**
     * Get the contracts that are applicable to the entity.
     *
     * @return Collection<int, interface-string<ConfiguresSchema>>
     */
    protected function getApplicableContracts(Schema $schema): Collection
    {
        $contractTypes = ['*'];
        $contractTypes[] = match (get_class($schema)) {
            ArraySchema::class => 'array',
            BooleanSchema::class => 'boolean',
            IntegerSchema::class => ['integer', 'number'],
            NumberSchema::class => 'number',
            ObjectSchema::class => 'object',
            StringSchema::class => 'string',
            default => [],
        };

        return collect(static::$contracts)
            ->only(Arr::flatten($contractTypes))
            ->values();
    }

    /**
     * Get the name of the configure method defined in the contract.
     *
     * If the contract is ConfiguresIntegerSchema, also get the
     * methods defined in ConfiguresNumberSchema.
     *
     * @param  interface-string<ConfiguresSchema>  $contract
     * @return Collection<int, string>
     */
    protected function getContractMethods(string $contract): Collection
    {
        $reflector = new ReflectionClass($contract);

        $methods = $reflector->getMethods(ReflectionMethod::IS_PUBLIC);

        return collect($methods)
            ->map(fn (ReflectionMethod $method) => $method->getName())
            ->when($contract === ConfiguresIntegerSchema::class, function ($methods) {
                return $methods->merge($this->getContractMethods(ConfiguresNumberSchema::class));
            });
    }
}
