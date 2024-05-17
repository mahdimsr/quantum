<?php

namespace App\Services\Exchange\Requests;

use App\Services\Exchange\Responses\AdjustPositionLeverageContract;

interface PositionRequestContract
{
    public function adjustPositionLeverage(string $symbol, string $marketType, string $marginMode, int $leverage): ?AdjustPositionLeverageContract;
}
