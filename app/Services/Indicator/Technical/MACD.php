<?php

namespace App\Services\Indicator\Technical;

use App\Services\Indicator\Entity\Candle;
use App\Services\Indicator\Facade\Indicator;

class MACD extends IndicatorStructure
{
    private int $shortPeriod = 9;
    private int $longPeriod = 9;

    public function setShortPeriod(int $shortPeriod)
    {
        $this->shortPeriod = $shortPeriod;
    }

    public function setLongPeriod(int $longPeriod)
    {
        $this->longPeriod = $longPeriod;
    }


    public function run(): array
    {
        $shortEMA = Indicator::EMA($this->candlesCollection, $this->shortPeriod);

        $longEMA = Indicator::EMA($this->candlesCollection, $this->longPeriod);

        $macdLine = array_map(function ($short, $long) {
            return $short - $long;
        }, $shortEMA, $longEMA);

        $macdLineCollection = collect($macdLine)->map(fn($item) => Candle::fromArray([
            'time' => null,
            'close' => $item,
            'open' => null,
            'high' => null,
            'low' => null,
            'volume' => null
        ]));

        $signalLine = Indicator::EMA($macdLineCollection);

        $macdCalculation = [];

        for ($i = 0; $i < count($macdLine); $i++) {
            $macdCalculation[] = [
                'macd_line' => $macdLine[$i],
                'signal_line' => $signalLine[$i]
            ];
        }

        return $macdCalculation;
    }

}
