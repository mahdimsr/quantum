<?php

namespace App\Services\Indicator\Technical;

use App\Services\Exchange\Repository\Candle;
use App\Services\Exchange\Repository\CandleCollection;

class VWMA extends IndicatorStructure
{

    public function __construct(CandleCollection $candlesCollection, int $period = 9)
    {
        parent::__construct($candlesCollection, $period);
    }

    public function run(): CandleCollection
    {
         $closeVolumeSMACollection = $this->calculateCloseVolumeSMA($this->candlesCollection);

         $volumeCollection = $this->calculateVolumeSMA($closeVolumeSMACollection);

         $vwmaCollection = $this->calculateVWMA($volumeCollection);

         return $vwmaCollection;
    }

    private function calculateCloseVolumeSMA(CandleCollection $candleCollection): CandleCollection
    {
        return $candleCollection->map(function (Candle $candle, $key) {

            $closeVolumeSum = $this->candlesCollection->map(fn(Candle $sliceCandle) => $sliceCandle->getClose() * $sliceCandle->getVolume())
                ->slice($key, $this->period)
                ->sum();

            $closeVolumeSMA = $closeVolumeSum / $this->period;

            $candle->setMeta([
                "close-volume-sma-$this->period" => $closeVolumeSMA,
            ]);

            return $candle;
        });
    }

    private function calculateVolumeSMA(CandleCollection $candleCollection): CandleCollection
    {
        return $candleCollection->map(function (Candle $candle, $key) {

            $volumesSum = $this->candlesCollection->volumes()->slice($key, $this->period)->sum();

            $volumesSMA = $volumesSum / $this->period;

            $candle->setMeta([
                "volume-sma-$this->period" => $volumesSMA,
            ]);

            return $candle;
        });
    }

    private function calculateVWMA(CandleCollection $candleCollection): CandleCollection
    {
        return $candleCollection->map(function (Candle $candle, $key) {

            $vwma = $candle->getMeta()["close-volume-sma-$this->period"] / $candle->getMeta()["volume-sma-$this->period"];

             $candle->setMeta([
                 "vwma-$this->period" => round($vwma, 8),
            ]);

             return $candle;
        });
    }
}
