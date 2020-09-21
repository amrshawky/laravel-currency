# Laravel Currency
![Tests](https://github.com/amrshawky/laravel-currency/workflows/Tests/badge.svg?branch=master)

Laravel currency is a simple package for currency conversion, latest and historical exchange rates based on the free API [exchangerate.host](https://exchangerate.host "exchangerate.host Homepage") - no API keys needed!

## Requirements
- PHP >= 7.2
- Laravel >= 6.0
- guzzlehttp >= 6.0

## Installation
```
composer require amrshawky/laravel-currency
```

## Usage

### 1. Currency Conversion
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
#### Available Methods
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

### 2. Latest Rates
To get latest rates you may chain the methods like so: 
```php
Currency::rates()
        ->latest()
        ->get();
```
This will return an `array` of all available currencies or `null` on failure.

#### Available Methods
- Just like currency conversion you may chain any of the available methods like so:
```php
Currency::rates()
        ->latest()
        ->symbols(['USD', 'EUR', 'EGP']) //An array of currency codes to limit output currencies
        ->base('GBP') //Changing base currency. Enter the three-letter currency code of your preferred base currency.
        ->amount(5.66) //Specify the amount to be converted
        ->round(2) //Round numbers to decimal places
        ->source('ecb') //Switch data source between forex `default` or bank view
        ->get();
```

### 3. Historical Rates
Historical rates are available for most currencies all the way back to the year of 1999.
```php
Currency::rates()
        ->historical('2020-01-01') // `YYYY-MM-DD` Required date parameter to get the rates for
        ->get();
```
Same as latest rates you may chain any of the available methods like so: 
```php
Currency::rates()
        ->historical('2020-01-01')
        ->symbols(['USD', 'EUR', 'EGP'])
        ->base('GBP')
        ->amount(5.66)
        ->round(2)
        ->source('ecb')
        ->get();
```
More information regarding list of bank sources [here](https://api.exchangerate.host/sources "List of bank sources")

For a list of all supported symbols [here](https://api.exchangerate.host/symbols "List of supported symbols")

## More features
Coming soon!

## License
The MIT License (MIT). Please see [LICENSE](../master/LICENSE) for more information.
