<?php

namespace App\Services\Indicator\Facade;

use App\Services\Indicator\IndicatorService;
use Illuminate\Support\Facades\Facade;

class Indicator extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return IndicatorService::class;
    }
}
