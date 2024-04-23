<?php

namespace App\Services\Indicator\Technical;

use App\Services\Exchange\Repository\Candle;
use App\Services\Exchange\Repository\CandleCollection;
use Exception;

abstract class IndicatorStructure
{
    protected CandleCollection $candlesCollection;
    protected int $period;

    /**
     * @throws Exception
     */
    public function __construct(CandleCollection $candlesCollection, int $period = 9)
    {
        $this->candlesCollection = $candlesCollection;
        $this->period = $period;

    }

    abstract public function run(): mixed;
}
