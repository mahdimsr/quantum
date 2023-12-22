<?php

namespace App\Services\Indicator\Technical;

class EMA
{
    protected static int $period = 9;

    public static function period(int $period): static
    {
        self::$period = $period;

        return new self();
    }

    public static function run(array $data): float|int
    {

        // You might want to adjust the period based on your analysis
        $period = self::$period;

        // Calculate the multiplier
        $multiplier = 2 / ($period + 1);

        // Get the closing prices as an array
        $closingPrices = collect($data)->pluck('close')->toArray();

        // Calculate the initial SMA (Simple Moving Average)
        $sma = array_slice($closingPrices, 0, $period);
        $sma = array_sum($sma) / $period;

        // Calculate the initial EMA
        $ema = $sma;

        // Calculate EMA for the remaining data
        for ($i = $period; $i < count($closingPrices); $i++) {
            $ema = ($closingPrices[$i] - $ema) * $multiplier + $ema;
            // Store or use $ema as needed for your application
        }

        return $ema;
    }

}
