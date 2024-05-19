<?php

namespace App\Services\Exchange\Facade;

use App\Services\Exchange\Coinex\CoinexService;
use App\Services\Exchange\Coinex\Responses\OrderResponseAdapter;
use App\Services\Exchange\Enums\ExchangeResolutionEnum;
use App\Services\Exchange\Enums\OrderExecutionEnum;
use App\Services\Exchange\Enums\OrderTypeEnum;
use App\Services\Exchange\Repository\PositionLevelCollection;
use App\Services\Exchange\Responses\AdjustPositionLeverageContract;
use App\Services\Exchange\Responses\AdjustPositionMarginResponseContract;
use App\Services\Exchange\Responses\CandleResponseContract;
use App\Services\Exchange\Responses\ClosePositionResponseContract;
use Illuminate\Support\Facades\Facade;

/**
 * @method static CandleResponseContract market(string $symbol, string $period, string $limit = null)
 * @method static OrderResponseAdapter orders(string $marketType)
 * @method static mixed placeOrder(string $symbol, string $marketType, string $side, string $type, float $amount, float $price)
 * @method static AdjustPositionLeverageContract adjustPositionLeverage(string $symbol, string $marketType, string $marginMode, int $leverage)
 * @method static AdjustPositionMarginResponseContract adjustPositionMargin(string $symbol, string $marketType, string $amount)
 * @method static ClosePositionResponseContract closePosition(string $symbol, string $marketType, string $type, int $price, int $amount, ?string $customId = null)
 * @method static PositionLevelCollection positionLevel(string $symbol)
 */
class Exchange extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return CoinexService::class;
    }
}
