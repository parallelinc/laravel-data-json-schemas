# Laravel Data classes to JSON Schemas

[![Latest Version on Packagist](https://img.shields.io/packagist/v/basillangevin/laravel-data-schemas.svg?style=flat-square)](https://packagist.org/packages/basillangevin/laravel-data-schemas)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/basillangevin/laravel-data-schemas/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/basillangevin/laravel-data-schemas/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/basillangevin/laravel-data-schemas/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/basillangevin/laravel-data-schemas/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/basillangevin/laravel-data-schemas.svg?style=flat-square)](https://packagist.org/packages/basillangevin/laravel-data-schemas)

This package transforms [spatie/laravel-data](https://github.com/spatie/laravel-data) classes into JSON Schemas and includes built-in validation.

## Table of Contents

<details><summary>Click to expand</summary><p>


- [Installation](#installation)
- [Quick start](#quick-start)
  - [JsonSchema::toArray(...)](#jsonschematoarray)
  - [JsonSchema::collectToArray(...)](#jsonschemacollecttoarray)
- [Schema annotations](#schema-annotations)
  - [Setting the title](#setting-the-title)
  - [Setting the description](#setting-the-description)
  - [Setting the default value](#setting-the-default-value)
- [Validation rules](#validation-rules)
  - [Supported validation attributes](#supported-validation-attributes)
  - [Date attribute transformations](#date-attribute-transformations)
- [Type transformations](#type-transformations)
- [Testing](#testing)
- [Changelog](#changelog)
- [Contributing](#contributing)
- [Security Vulnerabilities](#security-vulnerabilities)
- [Credits](#credits)
- [License](#license)

</p></details>

## Installation

> **Note:** This package requires PHP 8.3+ and Laravel 11+.

You can install the package via composer:

```bash
composer require basillangevin/laravel-data-schemas
```

Optionally, you can publish the config file with:

```bash
php artisan vendor:publish --tag="laravel-data-schemas-config"
```

This is the contents of the published config file:

```php
use BasilLangevin\LaravelDataSchemas\Enums\JsonSchemaDialect;

return [
    /*
    |--------------------------------------------------------------------------
    | JSON Schema Dialect
    |--------------------------------------------------------------------------
    |
    | If this value is not null, a "$schema" keyword will be set to
    | this identifier in the root of each generated JSON Schema.
    | This value won't change how JSON Schemas are generated.
    */
    'dialect' => JsonSchemaDialect::Draft201909,
];
```

## Quick start

The `BasilLangevin\LaravelDataSchemas\Facades\JsonSchema` facade can transform any `Spatie\LaravelData\Data` class into a JSON Schema.

It includes four methods:

```php
/**
 * Transform a Spatie Data class into a JSON Schema.
 *
 * @param  class-string<Data>  $dataClass
 */
JsonSchema::toArray(string $dataClass): array;

/**
 * Transform a Spatie Data class into an array schema.
 * The "items" will be instances of the Data class.
 *
 * @param  class-string<Data>  $dataClass
 */
JsonSchema::collectToArray(string $dataClass): array;

/**
 * Transform a Spatie Data class into a JSON Schema object.
 * The object can then be modified with keyword methods.
 *
 * @param  class-string<Data>  $dataClass
 */
JsonSchema::make(string $dataClass): BasilLangevin\LaravelDataSchemas\Schemas\ObjectSchema;

/**
 * Transform a Spatie Data class into an ArraySchema object.
 * The object can then be modified with keyword methods.
 *
 * @param  class-string<Data>  $dataClass
 */
JsonSchema::collect(string $dataClass): BasilLangevin\LaravelDataSchemas\Schemas\ArraySchema;
```

### JsonSchema::toArray(...)

To demonstrate transforming a `Data` class into a JSON Schema, we'll create a simple `BikeData` class:

```php
use Spatie\LaravelData\Attributes\Validation\In;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Data;

class BikeData extends Data
{
    public function __construct(
        public string $brand,
        /** The model name of the bike. */
        public string $model,
        #[Min(2010)]
        public int $year,
        #[In(['red', 'blue', 'green'])]
        public ?string $color = null,
        public bool $is_electric = false,
    ) {}
}
```

Now, we can transform this `Data` class into a JSON Schema (as a PHP array):

```php
$schema = JsonSchema::toArray(BikeData::class);
```

This will generate the following JSON Schema. As you can see, the JSON Schema includes descriptions and validation rules to match each property's doc block and attributes:

```php
$schema = [
    '$schema' => 'https://json-schema.org/draft/2019-09/schema',
    'title' => 'Bike',
    'type' => 'object',
    'properties' => [
        'brand' => [
            'type' => 'string',
        ],
        'model' => [
            'type' => 'string',
            'description' => 'The model name of the bike.',
        ],
        'year' => [
            'type' => 'integer',
            'minimum' => 2010,
        ],
        'color' => [
            'default' => null,
            'type' => ['string', 'null'],
            'enum' => ['red', 'blue', 'green'],
        ],
        'is_electric' => [
            'default' => false,
            'type' => 'boolean',
        ],
    ],
    'required' => ['brand', 'model', 'year', 'is_electric'],
];
```

### JsonSchema::collectToArray(...)

We can also transform `Data` classes into a JSON Schema array whose items are instances of the `Data` class:

```php
$schema = JsonSchema::collectToArray(BikeData::class);
```

This will generate the following JSON Schema:

```php
$schema = [
    '$schema' => 'https://json-schema.org/draft/2019-09/schema',
    'items' => [
        'title' => 'Bike',
        'type' => 'object',
        'properties' => [
            ...
        ],
        'required' => ['brand', 'model', 'year', 'is_electric'],
    ],
];
```

## Schema annotations

This package supports the `title`, `description`, and `default` JSON Schema annotations.

### Setting the title

`Data` classes and their properties can have a JSON Schema `title` annotation which can be set three different ways (in order of precedence):

**1. Using the `BasilLangevin\LaravelDataSchemas\Attributes\Title` attribute:**

```php
#[Title('The title of a data class.')]
class BikeData extends Data
{
    public function __construct(
        #[Title('The title of a property.')]
        public string $brand,
    ) {}
}
```

**2. Using the summary of a PHPDoc block (when it also has a description):**

> **Note:** This method is not currently supported on Windows Servers.

```php
/**
 * The title of a data class.
 *
 * The description of a data class.
 */
class BikeData extends Data
{
    public function __construct(
        /**
         * The title of a property.
         *
         * The description of a property.
         */
        public string $brand,
    ) {}
}
```

**3. Using the name of the `Data` class:**

The class name will be used as the `title` if no other title is set. Any "Data" suffix will be removed, and the name will be title-cased and space-separated.

For example, `BikePartData` will become `Bike Part`.

### Setting the description

`Data` classes and their properties can have a JSON Schema `description` annotation which can be set four different ways (in order of precedence):

**1. Using the `BasilLangevin\LaravelDataSchemas\Attributes\Description` attribute:**

```php
#[Description('The description of a data class.')]
class BikeData extends Data
{
    public function __construct(
        #[Description('The description of a property.')]
        public string $brand,
    ) {}
}
```

**2. Using the description of an `@param` or `@var` tag of a PHPDoc block:**

```php
/**
 * @var string $model The description of the model property.
 */
class BikeData extends Data
{
    public function __construct(
        /** @param string $brand The description of the brand property. */
        public string $brand,
        public string $model,
    ) {}
}
```

**3. Using the summary of a PHPDoc block (when it has no description):**

> **Note:** This method is not currently supported on Windows Servers.

```php
/**
 * The description of a data class.
 */
class BikeData extends Data
{
    /**
     * The description of a property.
     */
    public string $brand;
}
```

**4. Using the description of a PHPDoc block (when it also has a summary):**

> **Note:** This method is not currently supported on Windows Servers.

```php
/**
 * The title of a data class.
 *
 * The description of a data class.
 */
class BikeData extends Data
{
    /**
     * The title of a property.
     *
     * The description of a property.
     */
    public string $brand;
}
```

### Setting the default value

The `default` JSON Schema annotation will be set to the default value of the property.

```php
class BikeData extends Data
{
    public function __construct(
        public string $brand = 'Trek',
    ) {}
}
```

## Validation rules

This package automatically transforms `spatie\laravel-data` validation attributes into JSON Schema keywords.

For example, the `Min` attribute will be transformed into a `minLength` keyword when the property is a string or a `minimum` keyword when the property is an integer:

```php
use Spatie\LaravelData\Attributes\Validation\Min;

class BikeData extends Data
{
    public function __construct(
        #[Min(3)]
        public string $name,
        #[Min(10)]
        public int $quantity,
    ) {}
}

$schema = JsonSchema::toArray(BikeData::class);
```

This will generate the following JSON Schema:

```php
$schema = [
    '$schema' => 'https://json-schema.org/draft/2019-09/schema',
    'title' => 'Bike',
    'type' => 'object',
    'properties' => [
        'name' => [
            'minLength' => 3,
        ],
        'quantity' => [
            'minimum' => 10,
        ],
    ],
];
```

### Supported validation attributes

This package supports most of the validation attributes available in `spatie\laravel-data`, as detailed in the table below. Custom validation attributes are not supported.

Validation attributes that can be transformed into JSON Schema validation keywords are indicated with ✅.

Because JSON Schema's validation keywords are somewhat limited, some validation attributes can only be transformed into custom annotations (indicated with ☑️). These attributes cannot be validated by any JSON Schema validator, so they must be validated by Laravel's validation system.

Finally, some validation attributes are not supported at all (indicated with ❌). Typically, these attributes exceed the scope of what JSON Schema supports, or they rely on database calls to be validated.

The following table summarizes the support for each attribute, grouped by JSON Schema's `type` keyword rather than the PHP type equivalent.

See the [Type transformations](#type-transformations) section for more details.

| **Attribute**            | **string** | **number** | **array** | **object** | **boolean** |
|--------------------------|:----------:|:----------:|:---------:|:----------:|:-----------:|
| **Accepted**             | ✅          | ✅          |           |            |             |
| **AcceptedIf**           | ❌          | ❌          |           |            |             |
| **ActiveUrl**            | ☑️         |            |           |            |             |
| **After**                | ☑️         |            |           |            |             |
| **AfterOrEqual**         | ☑️         |            |           |            |             |
| **Alpha**                | ✅          |            |           |            |             |
| **AlphaDash**            | ✅          |            |           |            |             |
| **AlphaNumeric**         | ✅          |            |           |            |             |
| **ArrayType**            |            |            |           | ✅          |             |
| **Bail**                 | ❌          | ❌          | ❌         | ❌          | ❌           |
| **Before**               | ☑️         |            |           |            |             |
| **BeforeOrEqual**        | ☑️         |            |           |            |             |
| **Between**              | ✅          | ✅          | ✅         | ✅          |             |
| **BooleanType**          | ✅          | ✅          |           |            | ✅           |
| **Confirmed**            | ☑️         | ☑️         | ☑️        | ☑️         | ☑️          |
| **CurrentPassword**      | ❌          |            |           |            |             |
| **Date**                 | ✅          |            |           |            |             |
| **DateEquals**           | ✅          |            |           |            |             |
| **DateFormat**           | ☑️         |            |           |            |             |
| **Declined**             | ✅          | ✅          |           |            | ✅           |
| **DeclinedIf**           | ❌          | ❌          |           |            | ❌           |
| **Different**            | ☑️         |            |           |            |             |
| **Digits**               |            | ✅          |           |            |             |
| **DigitsBetween**        |            | ✅          |           |            |             |
| **Dimensions**           | ❌          |            |           |            |             |
| **Distinct**             | ☑️         | ☑️         | ☑️        | ☑️         | ☑️          |
| **DoesntEndWith**        | ✅          |            |           |            |             |
| **DoesntStartWith**      | ✅          |            |           |            |             |
| **Email**                | ✅          |            |           |            |             |
| **EndsWith**             | ✅          |            |           |            |             |
| **Enum**                 | ✅          | ✅          |           |            |             |
| **ExcludeIf**            | ❌          | ❌          | ❌         | ❌          | ❌           |
| **ExcludeUnless**        | ❌          | ❌          | ❌         | ❌          | ❌           |
| **ExcludeWith**          | ❌          | ❌          | ❌         | ❌          | ❌           |
| **ExcludeWithout**       | ❌          | ❌          | ❌         | ❌          | ❌           |
| **Exists**               | ❌          | ❌          |           |            |             |
| **File**                 | ❌          |            |           |            |             |
| **Filled**               | ✅          | ✅          | ✅         | ✅          |             |
| **GreaterThan**          | ✅          | ✅          | ✅         | ✅          |             |
| **GreaterThanOrEqualTo** | ✅          | ✅          | ✅         | ✅          |             |
| **Image**                | ❌          |            |           |            |             |
| **In**                   | ✅          | ✅          |           |            |             |
| **InArray**              | ❌          | ❌          |           |            | ❌           |
| **IntegerType**          | ✅          |            |           |            |             |
| **IP**                   | ☑️         |            |           |            |             |
| **IPv4**                 | ✅          |            |           |            |             |
| **IPv6**                 | ✅          |            |           |            |             |
| **Json**                 | ☑️         |            |           |            |             |
| **LessThan**             | ✅          | ✅          | ✅         | ✅          |             |
| **LessThanOrEqualTo**    | ✅          | ✅          | ✅         | ✅          |             |
| **Lowercase**            | ✅          |            |           |            |             |
| **ListType**             |            |            | ❌         |            |             |
| **MacAddress**           | ☑️         |            |           |            |             |
| **Max**                  | ✅          | ✅          | ✅         | ✅          |             |
| **MaxDigits**            |            | ✅          |           |            |             |
| **MimeTypes**            | ❌          |            |           |            |             |
| **Mimes**                | ❌          |            |           |            |             |
| **Min**                  | ✅          | ✅          | ✅         | ✅          |             |
| **MinDigits**            |            | ✅          |           |            |             |
| **MultipleOf**           |            | ✅          |           |            |             |
| **NotIn**                | ✅          | ✅          |           |            |             |
| **NotRegex**             | ✅          |            |           |            |             |
| **Nullable**             | ✅          | ✅          | ✅         | ✅          | ✅           |
| **Numeric**              | ✅          |            |           |            |             |
| **Password**             | ❌          |            |           |            |             |
| **Present**              | ✅          | ✅          | ✅         | ✅          | ✅           |
| **Prohibited**           | ✅          | ✅          | ✅         | ✅          |             |
| **ProhibitedIf**         | ❌          | ❌          | ❌         | ❌          |             |
| **ProhibitedUnless**     | ❌          | ❌          | ❌         | ❌          |             |
| **Prohibits**            | ❌          | ❌          | ❌         | ❌          |             |
| **Regex**                | ✅          |            |           |            |             |
| **Required**             | ✅          | ✅          | ✅         | ✅          | ✅           |
| **RequiredIf**           | ❌          | ❌          | ❌         | ❌          | ❌           |
| **RequiredUnless**       | ❌          | ❌          | ❌         | ❌          | ❌           |
| **RequiredWith**         | ❌          | ❌          | ❌         | ❌          | ❌           |
| **RequiredWithAll**      | ❌          | ❌          | ❌         | ❌          | ❌           |
| **RequiredWithout**      | ❌          | ❌          | ❌         | ❌          | ❌           |
| **RequiredWithoutAll**   | ❌          | ❌          | ❌         | ❌          | ❌           |
| **RequiredArrayKeys**    | ❌          | ❌          | ❌         | ❌          | ❌           |
| **Rule**                 | ❌          | ❌          | ❌         | ❌          | ❌           |
| **Same**                 | ☑️         | ☑️         | ☑️        | ☑️         | ☑️          |
| **Size**                 | ✅          | ✅          | ✅         | ✅          |             |
| **Sometimes**            | ❌          | ❌          | ❌         | ❌          | ❌           |
| **StartsWith**           | ✅          |            |           |            |             |
| **StringType**           | ❌          |            |           |            |             |
| **TimeZone**             | ☑️         |            |           |            |             |
| **Unique**               | ❌          | ❌          |           |            |             |
| **Uppercase**            | ✅          |            |           |            |             |
| **Url**                  | ✅          |            |           |            |             |
| **Ulid**                 | ☑️         |            |           |            |             |
| **Uuid**                 | ✅          |            |           |            |             |

### Date attribute transformations

Every validation attribute that validates dates (except the `DateFormat` attribute) sets the `format` keyword to `date-time`. Therefore, all date attributes follow the ISO 8601 standard.

Relative date attribute values (e.g. `'now'`, `'yesterday'`, `'next week'`, etc.) are transformed into ISO 8601 date strings.

## Type transformations

JSON Schema only supports a few types (`array`, `boolean`, `integer`, `number`, `object`, `string`, and `null`), so this package transforms PHP types into the closest JSON Schema type.

The following types are transformed as follows. All other types are currently unsupported.

**Array**
Arrays are transformed into the `array` or `object` type depending on whether they have sequential integer keys.

**Boolean**
Booleans are transformed into the `boolean` type.

**Collection**
`Collection` types (including `DataCollection`) are transformed into the `array` type.

**Data**
`Data` classes are transformed into `object` types.

**DateTimeInterface**
`DateTimeInterface` types (including `DateTime`, `Carbon`, and `CarbonImmutable`) are transformed into the `string` type.

All date schemas include the `format` keyword with the value `date-time`. Therefore, all dates follow the ISO 8601 standard.

**Enum**
Backed enums are transformed into either the `string` or `integer` type depending on their backing type. Pure enums are not supported.

**Float**
Floats are transformed into the `number` type.

**Integer**
Integers are transformed into the `integer` type.

**Null**
Nulls are transformed into the `null` type.

**Object**
Objects are transformed into the `object` type.

**String**
Strings are transformed into the `string` type.

**Union**
Unions are transformed into a union of their constituent types. Unions that include `Data` types wrap their constituent type schemas in an `anyOf` schema. Otherwise, the constituent schemas are consolidated into a single schema.





## Testing

Each PHP file in this package has a co-located Pest test file named `{FileName}Test.php`.

This package also has integration tests in the `tests/Integration` directory. These integration tests are used to test complex `Data` class transformations including nested `Data` classes, collections of `Data` objects, recursive `Data` structures, and complex union types.

This package achieves 100% test coverage, 100% mutation coverage, and 100% PHPStan coverage coverage at level 10.

The following commands can be used to test the package:

```bash
# Run the standard test suite
./vendor/bin/pest --parallel

# Run the test suite and generate a coverage report
./vendor/bin/pest --coverage --parallel

# Run mutation tests
./vendor/bin/pest --mutate --parallel --covered-only

# Run PHPStan at level 10
./vendor/bin/phpstan analyse
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [BasilLangevin](https://github.com/BasilLangevin)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
