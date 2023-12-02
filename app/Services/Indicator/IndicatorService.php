<?php

namespace App\Services\Indicator;

use App\Services\Indicator\Technical\EMA;
use App\Services\Indicator\Technical\RSI;

class IndicatorService
{
    public function RSI(array $candlesArray, int $period): array
    {
        return RSI::period($period)->run($candlesArray);
    }

    public function EMA(array $candlesArray, int $period): array
    {
        return EMA::period($period)->run($candlesArray);
    }
}
