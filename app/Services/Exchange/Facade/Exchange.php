<?php

namespace App\Services\Exchange\Facade;

use App\Enums\PriceTypeEnum;
use App\Services\Exchange\BingX\BingXService;
use App\Services\Exchange\Coinex\CoinexService;
use App\Services\Exchange\Coinex\Responses\OrderListResponseAdapter;
use App\Services\Exchange\Enums\SideEnum;
use App\Services\Exchange\Enums\TypeEnum;
use App\Services\Exchange\Repository\Order;
use App\Services\Exchange\Repository\PositionLevelCollection;
use App\Services\Exchange\Repository\Target;
use App\Services\Exchange\Responses\OrderListResponseContract;
use App\Services\Exchange\Responses\SetLeverageResponseContract;
use App\Services\Exchange\Responses\AdjustPositionMarginResponseContract;
use App\Services\Exchange\Responses\AssetBalanceContract;
use App\Services\Exchange\Responses\CandleResponseContract;
use App\Services\Exchange\Responses\CoinsResponseContract;
use App\Services\Exchange\Responses\SetOrderResponseContract;
use App\Services\Exchange\Responses\PositionResponseContract;
use Illuminate\Support\Facades\Facade;

/**
 * @method static CandleResponseContract candles(string $symbol, string $period, string $limit = null)
 * @method static CoinsResponseContract coins()
 * @method static SetLeverageResponseContract setLeverage(string $symbol, SideEnum $side, string $leverage)
 * @method static OrderListResponseContract orders(?string $symbol = null)
 * @method static SetOrderResponseContract setOrder(string $symbol, TypeEnum $typeEnum, SideEnum $sideEnum, SideEnum $positionSide, float $amount, float $price, mixed $client_id = null, ?Target $takeProfit = null, ?Target $stopLoss = null)
 * @method static AdjustPositionMarginResponseContract adjustPositionMargin(string $symbol, string $marketType, string $amount)
 * @method static mixed closePosition(string $symbol, string $marketType, string $type, int $price, int $amount, ?string $customId = null)
 * @method static PositionLevelCollection positionLevel(string $symbol)
 * @method static PositionResponseContract setTakeProfit(string $symbol, PriceTypeEnum $takeProfitType, float $takeProfitPrice)
 * @method static PositionResponseContract setStopLoss(string $symbol, PriceTypeEnum $stopLossType, float $stopLossPrice)
 * @method static PositionResponseContract currentPosition(string $symbol)
 * @method static AssetBalanceContract futuresBalance()
 */
class Exchange extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return BingXService::class;
    }
}
