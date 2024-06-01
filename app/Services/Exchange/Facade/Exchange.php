<?php

namespace App\Services\Exchange\Facade;

use App\Enums\PriceTypeEnum;
use App\Services\Exchange\Coinex\CoinexService;
use App\Services\Exchange\Coinex\Responses\OrderResponseAdapter;
use App\Services\Exchange\Repository\Order;
use App\Services\Exchange\Repository\PositionLevelCollection;
use App\Services\Exchange\Responses\AdjustPositionLeverageContract;
use App\Services\Exchange\Responses\AdjustPositionMarginResponseContract;
use App\Services\Exchange\Responses\AssetBalanceContract;
use App\Services\Exchange\Responses\CandleResponseContract;
use App\Services\Exchange\Responses\PositionResponseContract;
use Illuminate\Support\Facades\Facade;

/**
 * @method static CandleResponseContract candles(string $symbol, string $period, string $limit = null)
 * @method static OrderResponseAdapter orders(string $marketType)
 * @method static Order placeOrder(string $symbol, string $marketType, string $side, string $type, float $amount, float $price)
 * @method static AdjustPositionLeverageContract adjustPositionLeverage(string $symbol, string $marketType, string $marginMode, int $leverage)
 * @method static AdjustPositionMarginResponseContract adjustPositionMargin(string $symbol, string $marketType, string $amount)
 * @method static mixed closePosition(string $symbol, string $marketType, string $type, int $price, int $amount, ?string $customId = null)
 * @method static PositionLevelCollection positionLevel(string $symbol)
 * @method static mixed setTakeProfit(string $symbol, string $marketType, string $takeProfitType, float $takeProfitPrice)
 * @method static PositionResponseContract setStopLoss(string $symbol, PriceTypeEnum $stopLossType, float $stopLossPrice)
 * @method static mixed currentPosition(string $symbol, string $marketType)
 * @method static AssetBalanceContract futuresBalance()
 */
class Exchange extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return CoinexService::class;
    }
}
