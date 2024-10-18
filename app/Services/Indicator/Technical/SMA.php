<?php

namespace App\Services\Indicator\Technical;

use App\Services\Exchange\Repository\Candle;
use App\Services\Exchange\Repository\CandleCollection;

class SMA extends IndicatorStructure
{
    public function run(): CandleCollection
    {
        return $this->candlesCollection->map(function (Candle $candle, $key) {

            $sum = $this->candlesCollection->closes()->slice($key, $this->period)->sum();

            $sma = $sum / $this->period;

            $candle->setMeta([
                "sma-$this->period" => round($sma, 8),
            ]);


            return $candle;
        });
    }
}
