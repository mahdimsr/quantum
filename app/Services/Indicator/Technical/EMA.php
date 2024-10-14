<?php

namespace App\Services\Indicator\Technical;

use App\Services\Exchange\Repository\Candle;
use App\Services\Exchange\Repository\CandleCollection;
use Illuminate\Support\Collection;

class EMA extends IndicatorStructure
{
    public function run(): CandleCollection
    {
        $alpha = 2 / ($this->period + 1);
        $length = $this->candlesCollection->count() - 1;
        $ema = [];

        $ema[$length] = $this->candlesCollection->get($length)->getClose();

        for ($i = $length - 1; $i >= 0 ; $i--) {

            $preEma = $ema[$i + 1];

            $ema[$i] = $alpha * $this->candlesCollection->get($i)->getClose() + (1 - $alpha) * $preEma;
        }

        return $this->candlesCollection->map(function (Candle $candle, $key) use ($ema) {

            $candle->setMeta([
                "ema-$this->period" => round($ema[$key], 4),
            ]);

            return $candle;
        });

    }
}
