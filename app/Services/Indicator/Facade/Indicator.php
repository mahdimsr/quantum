<?php

namespace App\Services\Indicator\Facade;

use App\Services\Indicator\IndicatorService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Facade;

/**
 * @method static float|int RSI(Collection $candlesCollection, int $period = 14)
 * @method static array EMA(Collection $candlesCollection, int $period = 9)
 * @method static array SMA(Collection $candlesCollection, int $period = 7)
 * @method static array StandardDeviation(Collection $candlesCollection, int $period = 5)
 * @method static array MACD(array $data, int $shortPeriod = 12, int $longPeriod = 26, int $signalPeriod = 9)
 * @method static array superTrend(array $highPriceArray,array $lowPriceArray, array $closePriceArray, int $period = 14, float $multiplier = 1.5)
 */
class Indicator extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return IndicatorService::class;
    }

    public static function averageTrueRange(array $high, array $low, array $close, int $length = 14): array
    {
        $trueRange = [];
        $averageTrueRange = [];

        for ($i = 1; $i < count($close); $i++) {
            $trueRange[] = max(
                $high[$i] - $low[$i],
                abs($high[$i] - $close[$i - 1]),
                abs($low[$i] - $close[$i - 1])
            );
        }

        $averageTrueRange[0] = array_sum(array_slice($trueRange, 0, $length)) / $length;

        for ($i = 1; $i < count($trueRange); $i++) {
//            $averageTrueRange[$i] = ($averageTrueRange[$i - 1] * ($length - 1) + $trueRange[$i]) / $length;
            $averageTrueRange[$i] = array_sum(array_slice($trueRange, $i, $length)) / $length;
        }

        return $averageTrueRange;
    }

}
