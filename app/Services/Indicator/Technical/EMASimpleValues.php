<?php

namespace App\Services\Indicator\Technical;

use Illuminate\Support\Collection;

class EMASimpleValues
{
    private array $values;
    private int $period;

    public function __construct(array $values, int $period = 9)
    {
        $this->values = $values;
        $this->period = $period;
    }

    public function run(): array
    {
        $alpha = 2 / ($this->period + 1);
        $length = count($this->values) - 1;

        $ema = [];
        $ema[$length] = $this->values[$length];

        for ($i = $length - 1; $i >= 0 ; $i--) {

            $preEma = $ema[$i + 1];

            $exactValue = $alpha * $this->values[$i] + (1 - $alpha) * $preEma;

            $ema[$i] = round($exactValue, 8);
        }

        return $ema;
    }
}
