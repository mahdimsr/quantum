<?php

namespace App\Services\Indicator\Strategy;

use App\Services\Exchange\Repository\Candle;
use App\Services\Exchange\Repository\CandleCollection;
use App\Services\Indicator\Facade\Indicator;

class LNLTrendCollection extends CandleCollection
{
    private CandleCollection $candleCollection;
    private static int $atrLength = 80;

    public function __construct($items)
    {
        parent::__construct($items);

        $this->candleCollection = CandleCollection::make($items);

        $this->candleCollection = $this->calculateTrendLine();

        $this->candleCollection = $this->calculateTrendCloud($this->candleCollection);

        $this->candleCollection = $this->calculateNotDefinedCloud($this->candleCollection);
    }

    public function currentTrendCloud(): string
    {
        return $this->candleCollection->first()->getMeta()['lnl-trend-cloud'];
    }

    public function currentTrendLine(): string
    {
        return $this->candleCollection->first()->getMeta()['lnl-trend-line'];
    }

    private function calculateTrendLine(): CandleCollection
    {
        $vwma8Collection  = Indicator::VWMA($this->candleCollection, 8);
        $vwma13Collection = Indicator::VWMA($vwma8Collection, 13);
        $vwma21Collection = Indicator::VWMA($vwma13Collection, 21);
        $vwma34Collection = Indicator::VWMA($vwma21Collection, 34);

        return $vwma34Collection->map(function (Candle $candle, $key) {

            $bullish = false;
            $bearish = false;

            $vwma8 = $candle->getMeta()['vwma-8'];
            $vwma13 = $candle->getMeta()['vwma-13'];
            $vwma21 = $candle->getMeta()['vwma-21'];
            $vwma34 = $candle->getMeta()['vwma-34'];

            if ($vwma8 > $vwma13 and $vwma13 > $vwma21 and $vwma21 > $vwma34) {

                $bullish = true;
            }

            if ($vwma8 < $vwma13 and $vwma13 < $vwma21 and $vwma21 < $vwma34) {

                $bearish = true;
            }

            $trend = 'normal';

            if ($bullish and $candle->getClose() >= $vwma13) {

                $trend = 'bullish';

            } else if ($bearish and $candle->getClose() <= $vwma13) {

                $trend = 'bearish';
            }

            $candle->setMeta([
                'lnl-trend-line' => $trend,
            ]);

            return $candle;
        });
    }

    private function calculateTrendCloud(CandleCollection $trendLineCandleCollection): CandleCollection
    {
        $trendLineCandleCollection = Indicator::EMA($trendLineCandleCollection, 13);

        $highs = $this->candleCollection->highs()->toArray();
        $lows = $this->candleCollection->lows()->toArray();
        $closes = $this->candleCollection->closes()->toArray();

        $trueRange = Indicator::trueRange($highs, $lows, $closes);

        $emaTr = Indicator::EMAWithSimpleValues($trueRange, 8);

        $atr = collect($emaTr)->map(fn($value) => (self::$atrLength/100) * $value )->toArray();

        return $trendLineCandleCollection->map(function (Candle $candle, $key) use ($atr) {

            $isUp = false;
            $isDown = false;
            $T = 0;

            $candle->setMeta([
                'atr' => $atr[$key]
            ]);

            if ($candle->getClose() > ($candle->getMeta()['ema-13'] + $atr[$key])) {
                $isUp = true;
                $T = 1;
            }

            if ($candle->getClose() < ($candle->getMeta()['ema-13'] - $atr[$key])) {
                $isDown = true;
                $T = -1;
            }

            if ($isUp) {

                $candle->setMeta([
                    'lnl-trend-cloud' => 'bullish',
                ]);

            } else if ($isDown) {

                $candle->setMeta([
                    'lnl-trend-cloud' => 'bearish',
                ]);

            } else {

                $candle->setMeta([
                    'lnl-trend-cloud' => 'not-defined',
                ]);
            }


            return $candle;
        });
    }

    private function calculateNotDefinedCloud(CandleCollection $lnlTrendCollection): CandleCollection
    {
        return $lnlTrendCollection->each(function (Candle $candle, $key) {

            return $candle->getMeta()['lnl-trend-cloud'] == 'not-defined';

        })->map(function (Candle $candle, $key) use ($lnlTrendCollection) {

            $lastValidCloudCandle = $lnlTrendCollection->filter(fn(Candle $candle, $index) => $index > $key and $candle->getMeta()['lnl-trend-cloud'] != 'not-defined')->first();

            if ($lastValidCloudCandle) {

                $candle->resetMeta('lnl-trend-cloud', $lastValidCloudCandle->getMeta()['lnl-trend-cloud']);
            }

            return $candle;
        });
    }
}
