<?php

namespace App\Services\Indicator\Technical;

use Illuminate\Support\Collection;

class EMA extends Indicator
{
    private array $closePriceArray;

    public function __construct(Collection $candlesCollection, int $period = 9)
    {
        parent::__construct($candlesCollection, $period);

        $this->closePriceArray = $this->candlesCollection->map(fn($item) => $item->close())->toArray();
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
