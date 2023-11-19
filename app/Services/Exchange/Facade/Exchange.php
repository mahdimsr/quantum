<?php

namespace App\Services\Exchange\Facade;

use App\Services\Exchange\Responses\AllOrdersResponseContract;
use App\Services\Exchange\Responses\OrderResponseContract;
use App\Services\Exchange\Responses\StatsResponseContract;
use Illuminate\Support\Facades\Facade;

/**
 * @method static AllOrdersResponseContract orders()
 * @method static OrderResponseContract order(string $coinName)
 * @method static StatsResponseContract marketStats(string $srcCurrency, string $dstCurrency)
 */
class Exchange extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'ExchangeService';
    }
}
