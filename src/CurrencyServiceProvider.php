<?php

namespace AmrShawky\LaravelCurrency;

use AmrShawky\CurrencyFactory;
use Illuminate\Foundation\Application as LaravelApplication;
use Illuminate\Support\ServiceProvider;

class CurrencyServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('Currency', function ($app) {
            return new CurrencyFactory($app->config->get('api-exchangerate'));
        });
    }

    public function boot()
    {
        $this->setupConfig();
    }

    protected function setupConfig(): void
    {
        $source = realpath(__DIR__.'/../config/api-exchangerate.php');
        if ($this->app instanceof LaravelApplication && $this->app->runningInConsole()) {
            $this->publishes([$source => config_path('api-exchangerate.php')]);
        } elseif ($this->app instanceof LumenApplication) {
            $this->app->configure('api-exchangerate');
        }
        $this->mergeConfigFrom($source, 'api-exchangerate');
    }
}