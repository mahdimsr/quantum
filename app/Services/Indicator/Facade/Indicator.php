<?php

namespace App\Services\Indicator\Facade;

use App\Services\Indicator\IndicatorService;
use Illuminate\Support\Facades\Facade;

/**
 * @method static float|int RSI(array $data, int $period = 14)
 * @method static array EMA(array $data, int $period = 9)
 * @method static array MACD(array $data, int $shortPeriod = 12, int $longPeriod = 26, int $signalPeriod = 9)
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

        for ($i = $length; $i < count($trueRange); $i++) {
            $averageTrueRange[$i] = ($averageTrueRange[$i - 1] * ($length - 1) + $trueRange[$i]) / $length;
        }

        return $averageTrueRange;
    }

}
