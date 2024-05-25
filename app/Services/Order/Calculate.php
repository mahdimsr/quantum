<?php

namespace App\Services\Order;

class Calculate
{
    /**
     * calculate if prices touched each other
     *
     * @param mixed $currentPrice
     * @param mixed $targetPrice
     * @param float $tolerancePercent
     * @return bool
     */
    public static function touched(mixed $currentPrice, mixed $targetPrice, float $tolerancePercent = 0): bool
    {
        $intCurrentPrice = intval($currentPrice);
        $intTargetPrice  = intval($targetPrice);

        $remainPercent = 100 - (($targetPrice / $currentPrice) * 100);

        return abs($remainPercent) <= $tolerancePercent;
    }

    public static function touchedByRange(mixed $currentValue, mixed $targetValue, float $tolerance): bool
    {
        $difference = $targetValue - $currentValue;

        return -$tolerance <= abs($difference) or abs($tolerance) <= $tolerance;
    }

    public static function target(mixed $price, float $percent)
    {
        $absPercent = abs($percent);

        $targetPrice = 0;

        if ($percent > 0) {

            $targetPrice = $price + ($price * ($absPercent / 100));
        }

        if ($percent < 0) {

            $targetPrice = $price - ($price * ($absPercent / 100));
        }

        return $targetPrice;
    }

    public static function maxOrderAmount($price, $totalAsset, $leverage): float
    {
        return ($leverage * $totalAsset) / $price;
    }
}
