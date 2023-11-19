<?php

namespace App\Services\Exchange\Facade;

use App\Services\Exchange\Enums\ExchangeResolutionEnum;
use App\Services\Exchange\Responses\AllOrdersResponseContract;
use App\Services\Exchange\Responses\OHLCResponseContract;
use App\Services\Exchange\Responses\OrderResponseContract;
use App\Services\Exchange\Responses\StatsResponseContract;
use Illuminate\Support\Facades\Facade;

/**
 * @method static AllOrdersResponseContract orders()
 * @method static OrderResponseContract order(string $coinName)
 * @method static StatsResponseContract marketStats(string $srcCurrency, string $dstCurrency)
 * @method static OHLCResponseContract ohlc(string $symbol, ExchangeResolutionEnum $resolutionEnum, int $to,int $from, int $countBack, int $page = 1)
 */
class Exchange extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'ExchangeService';
    }
}
