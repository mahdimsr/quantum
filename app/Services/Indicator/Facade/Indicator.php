<?php

namespace App\Services\Indicator\Facade;

use App\Services\Exchange\Repository\CandleCollection;
use App\Services\Indicator\IndicatorService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Facade;

/**
 * @method static float|int RSI(Collection $candlesCollection, int $period = 14)
 * @method static CandleCollection EMA(Collection $candlesCollection, int $period = 9)
 * @method static array EMAWithSimpleValues(array $values, int $period = 9)
 * @method static CandleCollection SMA(Collection $candlesCollection, int $period = 7)
 * @method static array StandardDeviation(Collection $candlesCollection, int $period = 5)
 * @method static array BollingerBands(Collection $candlesCollection, int $period = 20, float $multiplier = 2)
 * @method static array MACD(Collection $candlesCollection, int $shortPeriod = 12, int $longPeriod = 26, int $signalPeriod = 9)
 * @method static array superTrend(array $highPriceArray, array $lowPriceArray, array $closePriceArray, int $period = 14, float $multiplier = 1.5)
 * @method static CandleCollection VWMA(CandleCollection $candleCollection, int $period = 14)
 */
class Indicator extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return IndicatorService::class;
    }

    public static function trueRange(array $high, array $low, array $close): array
    {
        $trueRange = [];
        $count     = count($high) - 1;


        for ($i = 0; $i < $count; $i++) {

            $trueRange[] = max(
                $high[$i] - $low[$i],
                abs($high[$i] - $close[$i + 1]),
                abs($low[$i] - $close[$i + 1])
            );
        }

        $trueRange[] = $high[$count] - $low[$count];

        return $trueRange;
    }

    public static function averageTrueRange(array $high, array $low, array $close, int $length = 14): array
    {
        $trueRange        = [];
        $averageTrueRange = [];
        $alpha = 1/$length;

        $trueRange = self::trueRange($high, $low, $close);

        $count = count($trueRange) - 1;

        $averageTrueRange[0] = $trueRange[$count];

        for ($i = 1; $i <= $count; $i++) {

            $value = $alpha * $trueRange[$count - $i] + (1- $alpha) * $averageTrueRange[$i - 1];

            $value = round($value,8);

            $averageTrueRange[$i] = $value;
        }

        return array_reverse($averageTrueRange);
    }

    public static function crossover(array $a, array $b): array
    {
        $result = [];
        $length = min(count($a), count($b));

        for ($i = 1; $i < $length; $i++) {
            if ($a[$i] > $b[$i] && $a[$i - 1] <= $b[$i - 1]) {
                $result[] = true;
            } else {
                $result[] = false;
            }
        }

        // For the first element, there's no previous element to compare to, so we can't determine crossover
//        array_unshift($result, false);

        return $result;
    }

}
