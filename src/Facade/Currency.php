<?php

namespace AmrShawky\LaravelCurrency\Facade;

use Illuminate\Support\Facades\Facade;

class Currency extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'Currency';
    }
}