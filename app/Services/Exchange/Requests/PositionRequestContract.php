<?php

namespace App\Services\Exchange\Requests;

use App\Enums\PriceTypeEnum;
use App\Services\Exchange\Responses\SetLeverageResponseContract;
use App\Services\Exchange\Responses\AdjustPositionMarginResponseContract;
use App\Services\Exchange\Responses\ClosePositionResponseContract;
use App\Services\Exchange\Responses\PositionResponseContract;

interface PositionRequestContract
{
    public function currentPosition(string $symbol): ?PositionResponseContract;

    public function closePositionByPositionId(string $positionId, ?string $symbol = null): ?ClosePositionResponseContract;
}
