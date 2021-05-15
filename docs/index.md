# Laravel Currency
![Tests](https://github.com/amrshawky/laravel-currency/workflows/Tests/badge.svg?branch=master) ![Packagist License](https://img.shields.io/packagist/l/amrshawky/laravel-currency?color=success&label=License) ![Packagist Version](https://img.shields.io/packagist/v/amrshawky/laravel-currency?label=Packagist) ![Packagist Downloads](https://img.shields.io/packagist/dt/amrshawky/laravel-currency?color=success&label=Downloads)

Laravel currency is a simple package for current and historical currency exchange rates & crypto exchange rates. based on the free API [exchangerate.host](https://exchangerate.host "exchangerate.host Homepage") - no API keys needed!

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
To convert from one currency to another you may chain the methods:

```php
use AmrShawky\LaravelCurrency\Facade\Currency;

Currency::convert()
        ->from('USD')
        ->to('EUR')
        ->get();
```
This will return the converted amount or `null` on failure.

The amount to be converted is default to `1`, you may specify the amount:

```php
use AmrShawky\LaravelCurrency\Facade\Currency;

Currency::convert()
        ->from('USD')
        ->to('EUR')
        ->amount(50)
        ->get();
```
#### Available Methods
- Convert currency using historical exchange rates `YYYY-MM-DD`:

```php
use AmrShawky\LaravelCurrency\Facade\Currency;

Currency::convert()
        ->from('USD')
        ->to('EUR')
        ->date('2019-08-01')
        ->get();
```

- Round the converted amount to decimal places:

```php
use AmrShawky\LaravelCurrency\Facade\Currency;

Currency::convert()
        ->from('USD')
        ->to('EUR')
        ->round(2)
        ->get();
```

- You may also switch data source between forex `default`, bank view or crypto currencies:

```php
use AmrShawky\LaravelCurrency\Facade\Currency;

Currency::convert()
        ->from('BTC')
        ->to('ETH')
        ->source('crypto')
        ->get();
```

### 2. Latest Rates
To get latest rates you may chain the methods:

```php
use AmrShawky\LaravelCurrency\Facade\Currency;

Currency::rates()
        ->latest()
        ->get();

// ['USD' =>  1.215707, ...]

Currency::rates()
        ->latest()
        ->source('crypto')
        ->get();

// ['ETH' => 3398.61, ...]
```
This will return an `array` of all available currencies or `null` on failure.

#### Available Methods
- Just like currency conversion you may chain any of the available methods:

```php
use AmrShawky\LaravelCurrency\Facade\Currency;

Currency::rates()
        ->latest()
        ->symbols(['USD', 'EUR', 'EGP']) //An array of currency codes to limit output currencies
        ->base('GBP') //Changing base currency (default: EUR). Enter the three-letter currency code of your preferred base currency.
        ->amount(5.66) //Specify the amount to be converted
        ->round(2) //Round numbers to decimal places
        ->source('ecb') //Switch data source between forex `default`, bank view or crypto currencies.
        ->get();
```

### 3. Historical Rates
Historical rates are available for most currencies all the way back to the year of 1999.

```php
use AmrShawky\LaravelCurrency\Facade\Currency;

Currency::rates()
        ->historical('2020-01-01') //`YYYY-MM-DD` Required date parameter to get the rates for
        ->get();

// ['USD' =>  1.1185, ...]

Currency::rates()
        ->historical('2021-03-30')
        ->source('crypto')
        ->get();
        
// ['BTC' =>  2.0E-5, ...]
```
Same as latest rates you may chain any of the available methods:
```php
use AmrShawky\LaravelCurrency\Facade\Currency;

Currency::rates()
        ->historical('2020-01-01')
        ->symbols(['USD', 'EUR', 'CZK'])
        ->base('GBP')
        ->amount(5.66)
        ->round(2)
        ->source('ecb')
        ->get();
```
### 4. Timeseries Rates
Timeseries are for daily historical rates between two dates of your choice, with a maximum time frame of 365 days.
This will return an `array` or `null` on failure.

```php
use AmrShawky\LaravelCurrency\Facade\Currency;

Currency::rates()
        ->timeSeries('2021-05-01', '2021-05-02') //`YYYY-MM-DD` Required dates range parameters
        ->symbols(['USD']) //[optional] An array of currency codes to limit output currencies
        ->base('GBP') //[optional] Changing base currency (default: EUR). Enter the three-letter currency code of your preferred base currency.
        ->amount(5.66) //[optional] Specify the amount to be converted (default: 1)
        ->round(2) //[optional] Round numbers to decimal places
        ->source('ecb') //[optional] Switch data source between forex `default`, bank view or crypto currencies.
        ->get();
        
/**
[
    '2021-05-01' => [
        "USD" => 1.201995
    ],
    '2021-05-02' => [
        "USD" => 1.2027
    ]
]
 */
```

### 5. Fluctuations
Retrieve information about how currencies fluctuate on a day-to-day basis, with a maximum time frame of 365 days.
This will return an `array` or `null` on failure.

```php
use AmrShawky\LaravelCurrency\Facade\Currency;

Currency::rates()
        ->fluctuations('2021-03-29', '2021-04-15') //`YYYY-MM-DD` Required dates range parameters
        ->symbols(['USD']) //[optional] An array of currency codes to limit output currencies
        ->base('GBP') //[optional] Changing base currency (default: EUR). Enter the three-letter currency code of your preferred base currency.
        ->amount(5.66) //[optional] Specify the amount to be converted (default: 1)
        ->round(2) //[optional] Round numbers to decimal places
        ->source('ecb') //[optional] Switch data source between forex `default`, bank view or crypto currencies.
        ->get();
        
/**
 [
    'USD' => [
        "start_rate" => 1.376454, 
        "end_rate"   => 1.37816, 
        "change"     => -0.001706, 
        "change_pct" => -0.001239
        ]
 ]
 */
```

### Throwing Exceptions
The default behavior is to return `null` for errors that occur during the request _(connection timeout, DNS errors, client or server error status code, missing API success parameter, etc.)_.

If you would like to throw an exception instead, you may use the `throw` method, The `throw` method returns the currency instance, allowing you to chain other methods:

```php
use AmrShawky\LaravelCurrency\Facade\Currency;

Currency::convert()
        ->from('USD')
        ->to('EUR')
        ->amount(20)
        ->throw()
        ->get();
```

If you would like to perform some additional logic before the exception is thrown, you may pass a closure to the throw method:

```php
use AmrShawky\LaravelCurrency\Facade\Currency;

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
use AmrShawky\LaravelCurrency\Facade\Currency;

Currency::convert()
        ->from('USD')
        ->to('EUR')
        ->withoutVerifying()
        ->get();
```

- You may specify additional [Guzzle request options](https://docs.guzzlephp.org/en/stable/request-options.html "Guzzle request options") using the `withOptions` method. The `withOptions` method accepts an array of key / value pairs:

```php
use AmrShawky\LaravelCurrency\Facade\Currency;

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
use AmrShawky\LaravelCurrency\Facade\Currency;

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

### Testing
Currency uses Laravel facades which makes it easy to [mock](https://laravel.com/docs/8.x/mocking#mocking-facades "Mocking Laravel Facades") so it's not actually executed during the test:

```php
use AmrShawky\LaravelCurrency\Facade\Currency;

Currency::shouldReceive('convert')
        ->once()
        ->andReturn(1.50);


Currency::shouldReceive('rates')
         ->once()
         ->andReturn(['EUR' => 1,'USD' => 1.215707]);
```

More information regarding list of bank sources [here](https://api.exchangerate.host/sources "List of bank sources")

For a list of all supported symbols [here](https://api.exchangerate.host/symbols "List of supported symbols") and list of crypto currencies [here](https://api.exchangerate.host/cryptocurrencies)

## License
The MIT License (MIT). Please see [LICENSE](../master/LICENSE) for more information.
