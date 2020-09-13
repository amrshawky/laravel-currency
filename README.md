# Laravel Currency
![Tests](https://github.com/amrshawky/laravel-currency/workflows/Tests/badge.svg?branch=master)

Laravel currency is a simple package for converting currencies based on the free API [exchangerate.host](https://exchangerate.host "exchangerate.host Homepage") - no API keys needed!

## Minimum requirements
Laravel 6

## Installation
```
composer require amrshawky/laravel-currency
```

## Usage

To convert from one currency to another you may chain the methods like so: 
```php
Currency::convert()
        ->from('USD')
        ->to('EUR')
        ->get();
```
This will return the converted amount or `null` on failure.

The amount to be converted is default to `1`, you may specify the amount:

```php
Currency::convert()
        ->from('USD')
        ->to('EUR')
        ->amount(50)
        ->get();
```
## Available Methods
- Convert currency using historical exchange rates `YYYY-MM-DD`:

```php
Currency::convert()
        ->from('USD')
        ->to('EUR')
        ->date('2019-08-01')
        ->get();
```

- Round the converted amount to decimal places:

```php
Currency::convert()
        ->from('USD')
        ->to('EUR')
        ->round(2)
        ->get();
```

- You may also switch data source between forex `default` or bank view:

```php
Currency::convert()
        ->from('USD')
        ->to('EUR')
        ->source('ecb')
        ->get();
```
More information regarding list of bank sources [here](https://api.exchangerate.host/sources "List of bank sources")

## More features
coming soon!

## License
The MIT License (MIT). Please see [LICENSE](../master/LICENSE) for more information.
