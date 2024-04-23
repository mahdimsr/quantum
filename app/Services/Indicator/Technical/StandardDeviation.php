<?php

namespace App\Services\Indicator\Technical;

class StandardDeviation extends IndicatorStructure
{

    public function run(): array
    {
        $closeDataArray = $this->candlesCollection->closes()->toArray();

        $stdDev = [];
        for ($i = $this->period - 1; $i < count($closeDataArray); $i++) {
            $slice = array_slice($closeDataArray, $i - $this->period + 1, $this->period);
            $mean = array_sum($slice) / $this->period;
            $sumSquaredDeviations = array_sum(array_map(fn($x) => pow($x - $mean, 2), $slice));
            $stdDev[$i] = sqrt($sumSquaredDeviations / $this->period);
        }

        return $stdDev;
    }
}
