<?php

namespace App\Services\Exchange\Facade;

use App\Services\Exchange\Coinex\CoinexService;
use App\Services\Exchange\Enums\ExchangeResolutionEnum;
use App\Services\Exchange\Enums\OrderExecutionEnum;
use App\Services\Exchange\Enums\OrderTypeEnum;
use App\Services\Exchange\Responses\AllOrdersResponseContract;
use App\Services\Exchange\Responses\OHLCListResponseContract;
use App\Services\Exchange\Responses\OHLCResponseContract;
use App\Services\Exchange\Responses\GetOrderResponseContract;
use App\Services\Exchange\Responses\SetOrderResponseContract;
use App\Services\Exchange\Responses\StatsResponseContract;
use App\Services\Exchange\Responses\UserResponseContract;
use Illuminate\Support\Facades\Facade;

/**
 * @method static AllOrdersResponseContract orders()
 * @method static GetOrderResponseContract order(string $coinName)
 * @method static SetOrderResponseContract setOrder(OrderTypeEnum $orderBuyEnum, OrderExecutionEnum $orderExecutionEnum, string $srcCurrency, string $dstCurrency, string $amount, string $price, string $clientOrderId)
 * @method static StatsResponseContract marketStats(string $srcCurrency, string $dstCurrency)
 * @method static OHLCListResponseContract ohlc(string $symbol, ExchangeResolutionEnum $resolutionEnum, int $to,int $from, int $countBack, int $page = 1)
 * @method static UserResponseContract user()
 */
class Exchange extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return CoinexService::class;
    }
}
