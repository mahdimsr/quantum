<?php

namespace App\Services\Indicator;

use App\Services\Indicator\Exceptions\RSIException;
use App\Services\Indicator\Technical\EMA;
use App\Services\Indicator\Technical\MACD;
use App\Services\Indicator\Technical\RSI;
use App\Services\Indicator\Technical\SuperTrend;

class IndicatorService
{
    /**
     * @throws RSIException
     */
    public function RSI(array $data, int $period = 14): float|int
    {
        return RSI::period($period)->run($data);
    }

    public function EMA(array $data, int $period = 9): array
    {
        return EMA::period($period)->run($data);
    }

    public function MACD(array $data, int $shortPeriod = 12, int $longPeriod = 26, int $signalPeriod = 9): array
    {
        return MACD::shortPeriod($shortPeriod)->longPeriod($longPeriod)->signalPeriod($signalPeriod)->run($data);
    }

    public function superTrend(array $highPriceArray,array $lowPriceArray, array $closePriceArray, int $period = 14, float $multiplier = 1.5): array
    {
        return SuperTrend::period($period)->multiplier($multiplier)->highPriceArray($highPriceArray)->lowPriceArray($lowPriceArray)->closePriceArray($closePriceArray)->run();
    }
}
