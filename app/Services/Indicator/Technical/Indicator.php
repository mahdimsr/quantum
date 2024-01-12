<?php

namespace App\Services\Indicator\Technical;

use App\Services\Indicator\Entity\Candle;
use Exception;
use Illuminate\Support\Collection;

abstract class Indicator
{
    protected Collection $candlesCollection;

    /**
     * @throws Exception
     */
    public function __construct(Collection $candlesCollection)
    {
        if (!is_a($candlesCollection->random(1)->first(), Candle::class)){

            throw new Exception('collection item should be instance of ' . Candle::class);
        }

        $this->candlesCollection = $candlesCollection;
    }

    abstract public function run(): mixed;
}
