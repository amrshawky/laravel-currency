<?php

namespace AmrShawky\Currency;

use Illuminate\Support\Facades\Facade;

class CurrencyFacade extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'Currency';
    }
}