# Laravel Currency
![Tests](https://github.com/amrshawky/laravel-currency/workflows/Tests/badge.svg?branch=master) ![Packagist License](https://img.shields.io/packagist/l/amrshawky/laravel-currency?color=success&label=License) ![Packagist Version](https://img.shields.io/packagist/v/amrshawky/laravel-currency?label=Packagist) ![Packagist Downloads](https://img.shields.io/packagist/dt/amrshawky/laravel-currency?color=success&label=Downloads)

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
use AmrShawky\Currency\Facade\Currency;

Currency::convert()
        ->from('USD')
        ->to('EUR')
        ->get();
```
This will return the converted amount or `null` on failure.

The amount to be converted is default to `1`, you may specify the amount:

```php
use AmrShawky\Currency\Facade\Currency;

Currency::convert()
        ->from('USD')
        ->to('EUR')
        ->amount(50)
        ->get();
```
#### Available Methods
- Convert currency using historical exchange rates `YYYY-MM-DD`:

```php
use AmrShawky\Currency\Facade\Currency;

Currency::convert()
        ->from('USD')
        ->to('EUR')
        ->date('2019-08-01')
        ->get();
```

- Round the converted amount to decimal places:

```php
use AmrShawky\Currency\Facade\Currency;

Currency::convert()
        ->from('USD')
        ->to('EUR')
        ->round(2)
        ->get();
```

- You may also switch data source between forex `default` or bank view:

```php
use AmrShawky\Currency\Facade\Currency;

Currency::convert()
        ->from('USD')
        ->to('EUR')
        ->source('ecb')
        ->get();
```

### 2. Latest Rates
To get latest rates you may chain the methods: 
```php
use AmrShawky\Currency\Facade\Currency;

Currency::rates()
        ->latest()
        ->get();
```
This will return an `array` of all available currencies or `null` on failure.

#### Available Methods
- Just like currency conversion you may chain any of the available methods:
```php
use AmrShawky\Currency\Facade\Currency;

Currency::rates()
        ->latest()
        ->symbols(['USD', 'EUR', 'EGP']) //An array of currency codes to limit output currencies
        ->base('GBP') //Changing base currency (default: EUR). Enter the three-letter currency code of your preferred base currency.
        ->amount(5.66) //Specify the amount to be converted
        ->round(2) //Round numbers to decimal places
        ->source('ecb') //Switch data source between forex `default` or bank view
        ->get();
```

### 3. Historical Rates
Historical rates are available for most currencies all the way back to the year of 1999.
```php
use AmrShawky\Currency\Facade\Currency;

Currency::rates()
        ->historical('2020-01-01') // `YYYY-MM-DD` Required date parameter to get the rates for
        ->get();
```
Same as latest rates you may chain any of the available methods: 
```php
use AmrShawky\Currency\Facade\Currency;

Currency::rates()
        ->historical('2020-01-01')
        ->symbols(['USD', 'EUR', 'EGP'])
        ->base('GBP')
        ->amount(5.66)
        ->round(2)
        ->source('ecb')
        ->get();
```
### Throwing Exceptions
The default behavior is to return `null` for errors that occur during the request _(connection timeout, DNS errors, client or server error status code, missing API success parameter, etc.)_.

If you would like to throw an exception instead, you may use the `throw` method, The `throw` method returns the currency instance, allowing you to chain other methods:

```php
use AmrShawky\Currency\Facade\Currency;

Currency::convert()
        ->from('USD')
        ->to('EUR')
        ->amount(20)
        ->throw()
        ->get();
```

If you would like to perform some additional logic before the exception is thrown, you may pass a closure to the throw method:

```php
use AmrShawky\Currency\Facade\Currency;

Currency::convert()
        ->from('USD')
        ->to('EUR')
        ->amount(20)
        ->throw(function ($response, $e) {
            //
        })
        ->get();
```
### Other Methods

- You may use the `withoutVerifying` method to indicate that TLS certificates should not be verified when sending the request:

```php
use AmrShawky\Currency\Facade\Currency;

Currency::convert()
        ->from('USD')
        ->to('EUR')
        ->withoutVerifying()
        ->get();
```

- You may specify additional [Guzzle request options](https://docs.guzzlephp.org/en/stable/request-options.html "Guzzle request options") using the `withOptions` method. The `withOptions` method accepts an array of key / value pairs:

```php
use AmrShawky\Currency\Facade\Currency;

Currency::rates()
        ->historical('2021-04-30')
        ->withOptions([
            'debug'   => true,
            'timeout' => 3.0
        ])
        ->get();
```

- The `when` method will execute the given callback when the first argument given to the method evaluates to true:
```php
use AmrShawky\Currency\Facade\Currency;

Currency::rates()
        ->latest()
        ->when(true, function ($rates) {
            // will execute
            $rates->symbols(['USD', 'EUR', 'EGP'])
                  ->base('GBP');
        })
        ->when(false, function ($rates) {
            // won't execute
            $rates->symbols(['HKD']);
        })
        ->get();
```

More information regarding list of bank sources [here](https://api.exchangerate.host/sources "List of bank sources")

For a list of all supported symbols [here](https://api.exchangerate.host/symbols "List of supported symbols")

## More features
Coming soon!

## License
The MIT License (MIT). Please see [LICENSE](../master/LICENSE) for more information.
