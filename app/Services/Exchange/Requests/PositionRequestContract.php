<?php

namespace App\Services\Exchange\Requests;

use App\Services\Exchange\Responses\AdjustPositionLeverageContract;
use App\Services\Exchange\Responses\AdjustPositionMarginResponseContract;
use App\Services\Exchange\Responses\ClosePositionResponseContract;

interface PositionRequestContract
{
    public function closePosition(string $symbol, string $marketType, string $type, float $price, float $amount): mixed;
    public function adjustPositionLeverage(string $symbol, string $marketType, string $marginMode, int $leverage): ?AdjustPositionLeverageContract;
    public function adjustPositionMargin(string $symbol, string $marketType, string $amount): ?AdjustPositionMarginResponseContract;
    public function currentPosition(string $symbol, string $marketType): mixed;
    public function setTakeProfit(string $symbol, string $marketType, string $takeProfitType, float $takeProfitPrice): mixed;
    public function setStopLoss(string $symbol, string $marketType, string $stopLossType, float $stopLossPrice): mixed;
}
