<?php

namespace App\Services\Indicator\Technical;

class SMA extends Indicator
{
    public function run(): array
    {
        $closeDataArray = $this->candlesCollection->map(fn($item) => $item->getClose())->toArray();

        $sma = [];
        for ($i = $this->period - 1; $i < count($closeDataArray); $i++) {
            $sma[$i] = array_sum(array_slice($closeDataArray, $i - $this->period + 1, $this->period)) / $this->period;
        }

        return $sma;
    }
}
