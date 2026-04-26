<?php

namespace Sosupp\SlimerAccounting\Facades;

use Illuminate\Support\Facades\Facade;

class Accounting extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'accounting';
    }
}