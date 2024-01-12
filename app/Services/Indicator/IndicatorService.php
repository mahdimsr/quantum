<?php

namespace App\Services\Indicator;

use App\Services\Indicator\Exceptions\RSIException;
use App\Services\Indicator\Technical\EMA;
use App\Services\Indicator\Technical\MACD;
use App\Services\Indicator\Technical\RSI;
use App\Services\Indicator\Technical\SMA;
use App\Services\Indicator\Technical\StandardDeviation;
use App\Services\Indicator\Technical\SuperTrend;
use Illuminate\Support\Collection;

class IndicatorService
{
    /**
     * @throws \Exception
     */
    public function RSI(Collection $candlesCollection, int $period = 14): float|int
    {
        $rsi = new RSI($candlesCollection, $period);

        return $rsi->run();
    }

    /**
     * @throws \Exception
     */
    public function EMA(Collection $candlesCollection, int $period = 9): array
    {
        $ema = new EMA($candlesCollection,$period);

        return $ema->run();
    }

    /**
     * @throws \Exception
     */
    public function SMA(Collection $candlesCollection, int $period = 9): array
    {
        $sma = new SMA($candlesCollection,$period);

        return $sma->run();
    }

    /**
     * @throws \Exception
     */
    public function StandardDeviation(Collection $candlesCollection, int $period = 5): array
    {
        $sd = new StandardDeviation($candlesCollection,$period);

        return $sd->run();
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
