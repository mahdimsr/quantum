<?php

namespace App\Services;

use Exception;

class OrderService
{
    protected static string $positionType = 'long';

    /**
     * @throws Exception
     */
    public static function positionType(string $positionType): static
    {
        if (!in_array($positionType,['long','short'])){

            throw new Exception('positionType only should be: long or short');
        }

        self::$positionType = $positionType;

        return new self();
    }

    public static function priceCalculate(mixed $currentPrice, mixed $takeProfitPercent, mixed $stopLossPercent): array
    {
        $upsidePrice = (($takeProfitPercent/100) * $currentPrice) + $currentPrice;
        $downsidePrice = $currentPrice - (($stopLossPercent/100) * $currentPrice);

        return [
            'takeProfit' => self::$positionType == 'long' ? $upsidePrice : $downsidePrice,
            'stopLoss' => self::$positionType == 'long' ? $downsidePrice : $upsidePrice,
        ];
    }
}
