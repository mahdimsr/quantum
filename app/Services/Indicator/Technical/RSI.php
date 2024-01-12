<?php

namespace App\Services\Indicator\Technical;

use App\Services\Indicator\Exceptions\RSIException;

class RSI extends IndicatorStructure
{
    public function run(): int|float
    {
        $closePriceArray = $this->candlesCollection->map(fn($item) => $item->close())->toArray();

        // Calculate price changes
        $priceChanges = [];
        foreach ($closePriceArray as $key => $price) {
            if ($key > 0) {

                $priceChanges[] = $price - $closePriceArray[$key - 1];
            }
        }

        // Calculate average gains and losses
        $gains = [];
        $losses = [];
        for ($i = 0; $i < $this->period; $i++) {
            if ($priceChanges[$i] > 0) {
                $gains[] = $priceChanges[$i];
            } elseif ($priceChanges[$i] < 0) {
                $losses[] = abs($priceChanges[$i]);
            }
        }

        $averageGain = array_sum($gains) / $this->period;
        $averageLoss = array_sum($losses) / $this->period;

        // Calculate initial RS and RSI
        $rs = ($averageGain > 0) ? $averageGain / $averageLoss : 0;
        $rsi = 100 - (100 / (1 + $rs));

        // Calculate RSI for the remaining closePriceArray
        for ($i = $this->period; $i < count($closePriceArray); $i++) {
            $priceChange = $closePriceArray[$i] - $closePriceArray[$i - 1];

            if ($priceChange > 0) {
                $averageGain = ($averageGain * ($this->period - 1) + $priceChange) / $this->period;
                $averageLoss = $averageLoss * ($this->period - 1) / $this->period;
            } elseif ($priceChange < 0) {
                $averageLoss = ($averageLoss * ($this->period - 1) + abs($priceChange)) / $this->period;
                $averageGain = $averageGain * ($this->period - 1) / $this->period;
            }

            $rs = ($averageGain > 0) ? $averageGain / $averageLoss : 0;
            $rsi = 100 - (100 / (1 + $rs));
        }

        return $rsi;
    }
}


