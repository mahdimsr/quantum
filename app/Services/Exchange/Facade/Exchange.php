<?php

namespace App\Services\Exchange\Facade;

use App\Services\Exchange\Coinex\CoinexService;
use App\Services\Exchange\Coinex\Responses\OrderResponseAdapter;
use App\Services\Exchange\Enums\ExchangeResolutionEnum;
use App\Services\Exchange\Enums\OrderExecutionEnum;
use App\Services\Exchange\Enums\OrderTypeEnum;
use App\Services\Exchange\Responses\AdjustPositionLeverageContract;
use Illuminate\Support\Facades\Facade;

/**
 * @method static CandleResponseContract market(string $symbol, string $period, string $limit = null)
 * @method static OrderResponseAdapter orders(string $marketType)
 * @method static mixed placeOrder(string $symbol, string $marketType, string $side, string $type, int $amount, int $price)
 * @method static AdjustPositionLeverageContract adjustPositionLeverage(string $symbol, string $marketType, string $marginMode, int $leverage)
 */
class Exchange extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return CoinexService::class;
    }
}
