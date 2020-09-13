<?php

namespace AmrShawky\Currency;
use Illuminate\Support\ServiceProvider;

class CurrencyServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('Currency', function ($app) {
            return new Currency();
        });
    }

    public function boot()
    {

    }
}