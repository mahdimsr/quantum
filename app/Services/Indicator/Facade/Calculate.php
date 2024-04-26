<?php

namespace App\Services\Indicator\Facade;

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
    public static function touched(mixed $currentPrice, mixed $targetPrice,float $tolerancePercent = 0): bool
    {
        $intCurrentPrice = intval($currentPrice);
        $intTargetPrice = intval($targetPrice);

        $remainPercent = 100 - (($targetPrice/$currentPrice) * 100);

        return abs($remainPercent) == $tolerancePercent;
    }
}
