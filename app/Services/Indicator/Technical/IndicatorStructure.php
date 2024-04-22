<?php

namespace App\Services\Indicator\Technical;

use App\Services\Exchange\Repository\Candle;
use Exception;
use Illuminate\Support\Collection;

abstract class IndicatorStructure
{
    protected Collection $candlesCollection;
    protected int $period;

    /**
     * @throws Exception
     */
    public function __construct(Collection $candlesCollection, int $period = 9)
    {
        $this->period = $period;

        if (!is_a($candlesCollection->random(1)->first(), Candle::class)){

            throw new Exception('collection item should be instance of ' . Candle::class);
        }

        $this->candlesCollection = $candlesCollection;
    }

    abstract public function run(): mixed;
}
