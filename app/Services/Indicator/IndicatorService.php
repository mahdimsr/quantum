<?php

namespace App\Services\Indicator;

use App\Services\Indicator\Exceptions\RSIException;
use App\Services\Indicator\Technical\EMA;
use App\Services\Indicator\Technical\RSI;

class IndicatorService
{
    /**
     * @throws RSIException
     */
    public function RSI(array $candlesArray, int $period): float|int
    {
        return RSI::period($period)->run($candlesArray);
    }

    public function EMA(array $candlesArray, int $period): float|int
    {
        return EMA::period($period)->run($candlesArray);
    }
}
