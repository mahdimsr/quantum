<?php

namespace App\Services\Exchange\Facade;

use App\Enums\PriceTypeEnum;
use App\Services\Exchange\BingX\BingXService;
use App\Services\Exchange\Coinex\CoineXService;
use App\Services\Exchange\Coinex\Responses\OrderListResponseAdapter;
use App\Services\Exchange\Enums\SideEnum;
use App\Services\Exchange\Enums\TypeEnum;
use App\Services\Exchange\Repository\Order;
use App\Services\Exchange\Repository\PositionLevelCollection;
use App\Services\Exchange\Repository\Target;
use App\Services\Exchange\Responses\ClosePositionResponseContract;
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
 * @method static SetOrderResponseContract setOrder(string $symbol, TypeEnum $typeEnum, SideEnum $sideEnum, SideEnum $positionSide, float $amount, float $price, mixed $client_id = null, ?Target $takeProfit = null, ?Target $stopLoss = null)
 * @method static ClosePositionResponseContract closePosition(string $symbol, string $marketType, string $type, int $price, int $amount, ?string $customId = null)
 * @method static PositionResponseContract currentPosition(string $symbol)
 * @method static ClosePositionResponseContract closePositionByPositionId(string $positionId, string $symbol)
 * @method static AssetBalanceContract futuresBalance()
 * @method static PositionResponseContract setStopLoss(string $symbol, mixed $stopLossPrice, string $stopLossType)
 * @method static PositionResponseContract setTakeProfit(string $symbol, mixed $takeProfitPrice, string $takeProfitType)
 * @method static OrderListResponseContract orders(?string $symbol = null, ?array $orderIds = null)
 * @method static PositionResponseContract positionHistory(string $symbol, string $positonId)
 */
class Exchange extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return CoineXService::class;
    }
}
