<?php

namespace AmrShawky\LaravelCurrency;

use AmrShawky\CurrencyFactory;
use Illuminate\Support\ServiceProvider;

class CurrencyServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('Currency', function ($app) {
            return new CurrencyFactory();
        });
    }

    public function boot()
    {

    }
}