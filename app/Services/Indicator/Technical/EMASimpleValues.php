<?php

namespace App\Services\Indicator\Technical;

use Illuminate\Support\Collection;

class EMASimpleValues
{
    private array $closePriceArray;
    private int $period;

    public function __construct(array $values, int $period = 9)
    {
        $this->closePriceArray = $values;
        $this->period = $period;
    }

    public function run(): array
    {
        $multiplier = 2 / ($this->period + 1);
        $ema = [];
        $ema[0] = $this->closePriceArray[0];

        for ($i = 1; $i < count($this->closePriceArray); $i++) {
            $ema[$i] = ($this->closePriceArray[$i] - $ema[$i - 1]) * $multiplier + $ema[$i - 1];
        }

        return $ema;
    }
}
