<?php

namespace App\Services\Indicator\Technical;

class SMA extends IndicatorStructure
{
    public function run(): array
    {
        $closeDataArray = $this->candlesCollection->map(fn($item) => $item->getClose())->toArray();

        $sma = [];
        for ($i = $this->period - 1; $i < count($closeDataArray); $i++) {
            $sma[] = array_sum(array_slice($closeDataArray, $i - $this->period + 1, $this->period)) / $this->period;
        }

        return $sma;
    }
}
