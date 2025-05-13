<?php

namespace App\Services\Exchange\Requests;

use App\Services\Exchange\Responses\ClosePositionResponseContract;
use App\Services\Exchange\Responses\PositionResponseContract;

interface PositionRequestContract
{
    public function currentPosition(string $symbol): ?PositionResponseContract;
    public function closePositionByPositionId(string $positionId, ?string $symbol = null): ?ClosePositionResponseContract;
    public function setStopLoss(string $symbol, mixed $stopLossPrice, string $stopLossType): ?PositionResponseContract;
    public function setTakeProfit(string $symbol, mixed $takeProfitPrice, string $takeProfitType): ?PositionResponseContract;
    public function positionHistory(string $symbol, string $positonId): ?PositionResponseContract;
}
