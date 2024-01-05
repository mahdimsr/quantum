<?php

namespace App\Services\Indicator\Technical;

use App\Services\Indicator\Facade\Indicator;

class SuperTrend
{
    protected static int $period = 14;
    protected static float $multiplier = 1.5;
    protected static array $highPriceArray;
    protected static array $lowPriceArray;
    protected static array $closePriceArray;

    public static function period(int $period): static
    {
        self::$period = $period;

        return new self();
    }

    public static function multiplier(float $multiplier): static
    {
        self::$multiplier = $multiplier;

        return new self();
    }

    public static function highPriceArray(array $highPriceArray):static
    {
        self::$highPriceArray = $highPriceArray;

        return new self();
    }

    public static function lowPriceArray(array $lowPriceArray):static
    {
        self::$lowPriceArray = $lowPriceArray;

        return new self();
    }

    public static function closePriceArray(array $closePriceArray):static
    {
        self::$closePriceArray = $closePriceArray;

        return new self();
    }

    public static function run(): array
    {
        $atr = Indicator::averageTrueRange(self::$highPriceArray,self::$lowPriceArray,self::$closePriceArray,self::$period);

        $supertrend = [];

        for ($i = 0; $i < count($atr); $i++) {
            $upperBand = self::$closePriceArray[$i] + ($atr[$i] * self::$multiplier);
            $lowerBand = self::$closePriceArray[$i] - ($atr[$i] * self::$multiplier);

            // Determine the Supertrend direction based on the close price
            if ($i > 0 && self::$closePriceArray[$i - 1] > $supertrend[$i - 1]) {
                $supertrend[$i] = max($upperBand, $supertrend[$i - 1]);
            } else {
                $supertrend[$i] = $lowerBand;
            }
        }

        return $supertrend;
    }
}
