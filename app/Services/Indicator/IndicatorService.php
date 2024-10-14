<?php

namespace App\Services\Indicator;

use App\Services\Exchange\Repository\CandleCollection;
use App\Services\Indicator\Exceptions\RSIException;
use App\Services\Indicator\Technical\BollingerBands;
use App\Services\Indicator\Technical\EMA;
use App\Services\Indicator\Technical\EMASimpleValues;
use App\Services\Indicator\Technical\MACD;
use App\Services\Indicator\Technical\RSI;
use App\Services\Indicator\Technical\SMA;
use App\Services\Indicator\Technical\StandardDeviation;
use App\Services\Indicator\Technical\SuperTrend;
use App\Services\Indicator\Technical\VWMA;
use Illuminate\Support\Collection;

class IndicatorService
{
    /**
     * @throws \Exception
     */
    public function RSI(CandleCollection $candlesCollection, int $period = 14): float|int
    {
        $rsi = new RSI($candlesCollection, $period);

        return $rsi->run();
    }

    /**
     * @throws \Exception
     */
    public function EMA(CandleCollection $candlesCollection, int $period = 9): array
    {
        $ema = new EMA($candlesCollection,$period);

        return $ema->run();
    }

    /**
     * @throws \Exception
     */
    public function EMAWithSimpleValues(array $values, int $period = 9): array
    {
        $ema = new EMASimpleValues($values, $period);

        return $ema->run();
    }

    /**
     * @throws \Exception
     */
    public function SMA(CandleCollection $candlesCollection, int $period = 7): CandleCollection
    {
        $sma = new SMA($candlesCollection,$period);

        return $sma->run();
    }

    /**
     * @throws \Exception
     */
    public function StandardDeviation(CandleCollection $candlesCollection, int $period = 5): array
    {
        $sd = new StandardDeviation($candlesCollection,$period);

        return $sd->run();
    }

    /**
     * @throws \Exception
     */
    public function BollingerBands(CandleCollection $candlesCollection, int $period = 20 , float $multiplier = 2): array
    {
        $bb = new BollingerBands($candlesCollection,$period);

        $bb->setMultiplier($multiplier);

        return $bb->run();
    }

    /**
     * @throws \Exception
     */
    public function MACD(CandleCollection $candlesCollection, int $shortPeriod = 12, int $longPeriod = 26, int $signalPeriod = 9): array
    {
       $macd = new MACD($candlesCollection,$signalPeriod);
       $macd->setLongPeriod($longPeriod);
       $macd->setShortPeriod($shortPeriod);

       return $macd->run();
    }

    public function superTrend(array $highPriceArray,array $lowPriceArray, array $closePriceArray, int $period = 14, float $multiplier = 1.5): array
    {
        return SuperTrend::period($period)->multiplier($multiplier)->highPriceArray($highPriceArray)->lowPriceArray($lowPriceArray)->closePriceArray($closePriceArray)->run();
    }

    public function VWMA(CandleCollection $candleCollection, int $period = 14): CandleCollection
    {
        $vwma = new VWMA($candleCollection,$period);

        return $vwma->run();
    }
}
