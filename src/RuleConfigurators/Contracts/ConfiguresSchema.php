<?php

namespace BasilLangevin\LaravelDataJsonSchemas\RuleConfigurators\Contracts;

/**
 * Each ConfiguresSchema interface defines a specific type
 * that the RuleConfigurator supports. When configuring
 * a schema, applicable rule methods will be called.
 *
 * For example, when a StringSchema has a Validation Rule that
 * supports string properties, both "configureStringSchema"
 * and "configureAnySchema" will be called for the rule.
 */
interface ConfiguresSchema {}
