<?php

namespace App\Services\Indicator\Facade;

use App\Services\Indicator\IndicatorService;
use Illuminate\Support\Facades\Facade;

/**
 * @method static float|int RSI(array $data, int $period = 14)
 * @method static array EMA(array $data, int $period = 9)
 */
class Indicator extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return IndicatorService::class;
    }
}
