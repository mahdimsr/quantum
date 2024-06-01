<?php

namespace App\Services\Exchange\Requests;

use App\Enums\PriceTypeEnum;
use App\Services\Exchange\Responses\AdjustPositionLeverageContract;
use App\Services\Exchange\Responses\AdjustPositionMarginResponseContract;
use App\Services\Exchange\Responses\ClosePositionResponseContract;
use App\Services\Exchange\Responses\PositionResponseContract;

interface PositionRequestContract
{
    public function closePosition(string $symbol, string $marketType, string $type, float $price, float $amount): mixed;
    public function adjustPositionLeverage(string $symbol, string $marketType, string $marginMode, int $leverage): ?AdjustPositionLeverageContract;
    public function adjustPositionMargin(string $symbol, string $marketType, string $amount): ?AdjustPositionMarginResponseContract;
    public function currentPosition(string $symbol, string $marketType): mixed;
    public function setTakeProfit(string $symbol, PriceTypeEnum $takeProfitType, float $takeProfitPrice): ?PositionResponseContract;
    public function setStopLoss(string $symbol, PriceTypeEnum $stopLossType, float $stopLossPrice): ?PositionResponseContract;
}
