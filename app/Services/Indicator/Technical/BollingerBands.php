<?php

namespace App\Services\Indicator\Technical;

use App\Services\Indicator\Facade\Indicator;

class BollingerBands extends IndicatorStructure
{
    private float $multiplier;

    public function setMultiplier(float $multiplier)
    {
        $this->multiplier = $multiplier;
    }

    public function run(): array
    {
        $closeDataArray = $this->candlesCollection->map(fn($item) => $item->getClose())->toArray();

        $sma = Indicator::SMA($this->candlesCollection, $this->period);
        $stdDev = Indicator::StandardDeviation($this->candlesCollection, $this->period);

        $upperBand = array_map(function ($m, $s) {
            return $m + ($s * $this->multiplier);
        }, $sma, $stdDev);

        $lowerBand = array_map(function ($m, $s) {
            return $m - ($s * $this->multiplier);
        }, $sma, $stdDev);

        $bollingerBandsCalculation = [];

        for ($i = 0; $i < count($sma); $i++) {

            $bollingerBandsCalculation[] = [
                'upper_band' => $upperBand[$i],
                'middle_band' => $sma[$i],
                'lower_band' => $lowerBand[$i]
            ];
        }

        return $bollingerBandsCalculation;
    }
}
