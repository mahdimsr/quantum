<?php

namespace App\Services\Exchange\Requests;

use App\Services\Exchange\Responses\AdjustPositionLeverageContract;
use App\Services\Exchange\Responses\AdjustPositionMarginResponseContract;

interface PositionRequestContract
{
    public function adjustPositionLeverage(string $symbol, string $marketType, string $marginMode, int $leverage): ?AdjustPositionLeverageContract;
    public function adjustPositionMargin(string $symbol, string $marketType, string $amount): ?AdjustPositionMarginResponseContract;
}
