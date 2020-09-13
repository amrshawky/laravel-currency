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
```
Currency::convert()
        ->from('USD')
        ->to('EUR')
        ->amount(50)
        ->get();
```
This will return the converted amount or `null` on failure.

You may also round the numbers to decimal place like so:
```
Currency::convert()
        ->from('USD')
        ->to('EUR')
        ->amount(50)
        ->round(2)
        ->get();
```

##### More features are coming soon!

## License
The MIT License (MIT). Please see [LICENSE](../master/LICENSE) for more information.
