<?php

namespace App\Services\Indicator\Technical;

use App\Services\Indicator\Exceptions\RSIException;

class RSI
{
    protected static int $period = 14;

    public static function period(int $period): static
    {
        self::$period = $period;

        return new self();
    }

    /**
     * @throws RSIException
     */
    public static function run(array $data): float|int
    {
        $period = 14;

        // Calculate price changes
        $priceChanges = [];
        foreach ($data as $key => $price) {
            if ($key > 0) {

                if (!array_key_exists('close', $price)){
                    throw RSIException::keyNotExists('close');
                }

                $priceChanges[] = $price['close'] - $data[$key - 1]['close'];
            }
        }

        // Calculate average gains and losses
        $gains = [];
        $losses = [];
        for ($i = 0; $i < $period; $i++) {
            if ($priceChanges[$i] > 0) {
                $gains[] = $priceChanges[$i];
            } elseif ($priceChanges[$i] < 0) {
                $losses[] = abs($priceChanges[$i]);
            }
        }

        $averageGain = array_sum($gains) / $period;
        $averageLoss = array_sum($losses) / $period;

        // Calculate initial RS and RSI
        $rs = ($averageGain > 0) ? $averageGain / $averageLoss : 0;
        $rsi = 100 - (100 / (1 + $rs));

        // Calculate RSI for the remaining data
        for ($i = $period; $i < count($data); $i++) {
            $priceChange = $data[$i]['close'] - $data[$i - 1]['close'];

            if ($priceChange > 0) {
                $averageGain = ($averageGain * ($period - 1) + $priceChange) / $period;
                $averageLoss = $averageLoss * ($period - 1) / $period;
            } elseif ($priceChange < 0) {
                $averageLoss = ($averageLoss * ($period - 1) + abs($priceChange)) / $period;
                $averageGain = $averageGain * ($period - 1) / $period;
            }

            $rs = ($averageGain > 0) ? $averageGain / $averageLoss : 0;
            $rsi = 100 - (100 / (1 + $rs));
        }

        return $rsi;
    }

}


