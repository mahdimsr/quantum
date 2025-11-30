<?php

namespace App\Services\Strategy;

use App\Enums\PositionTypeEnum;
use App\Services\Exchange\Repository\CandleCollection;

interface StrategyContract
{
    public function signal(CandleCollection $candleCollection): ?PositionTypeEnum;
}
