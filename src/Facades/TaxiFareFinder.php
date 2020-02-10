<?php

namespace TomOehlrich\TaxiFareFinder\Facades;

use Illuminate\Support\Facades\Facade;

class TaxiFareFinder extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'taxifarefinder';
    }
}
