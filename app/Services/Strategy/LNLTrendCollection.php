<?php

namespace App\Services\Strategy;

use App\Services\Exchange\Repository\Candle;
use App\Services\Exchange\Repository\CandleCollection;
use App\Services\Indicator\Facade\Indicator;
use Illuminate\Support\Collection;

class LNLTrendCollection extends CandleCollection
{
    private CandleCollection $candleCollection;
    private static int $atrLength = 80;

    public function __construct($items)
    {
        parent::__construct($items);

        $this->candleCollection = CandleCollection::make($items);

        $this->calculateTrendLine();

        $this->calculateTrendCloud();
    }

    private function calculateTrendLine(): void
    {
        $ema8  = Indicator::EMA($this->candleCollection, 8);
        $ema13 = Indicator::EMA($this->candleCollection, 13);
        $ema21 = Indicator::EMA($this->candleCollection, 21);
        $ema34 = Indicator::EMA($this->candleCollection, 34);

        $this->candleCollection->map(function (Candle $candle, $key) use ($ema8, $ema13, $ema21, $ema34) {

            $bullish = false;
            $bearish = false;

            if ($ema8[$key] > $ema13[$key] and $ema13[$key] > $ema21[$key] and $ema21[$key] > $ema34[$key]) {

                $bullish = true;
            }

            if ($ema8[$key] < $ema13[$key] and $ema13[$key] < $ema21[$key] and $ema21[$key] < $ema34[$key]) {

                $bearish = true;
            }

            $trend = 'normal';

            if ($bullish and $candle->getClose() >= $ema13[$key]) {

                $trend = 'bullish';

            } else if ($bearish and $candle->getClose() <= $ema13[$key]) {

                $trend = 'bearish';
            }

            $candle->setMeta(['lnl-trend-line' => $trend]);
        });
    }

    private function calculateTrendCloud(): void
    {
        $highs = $this->candleCollection->highs()->toArray();
        $lows = $this->candleCollection->lows()->toArray();
        $closes = $this->candleCollection->closes()->toArray();

        $trueRange = Indicator::trueRange($highs, $lows, $closes);

        $ema13 = Indicator::EMA($this->candleCollection, 13);
        $trEma13 = Indicator::EMAWithSimpleValues($trueRange, 13);

        $atr = collect($trEma13)->map(fn($value) => (self::$atrLength/100) * $value )->toArray();

        $this->candleCollection->map(function (Candle $candle, $key) use ($atr, $ema13) {

            $isUp = false;
            $isDown = false;
            $T = 0;

            if ($candle->getClose() > ($ema13[$key] + $atr[$key])) {
                $isUp = true;
                $T = 1;
            }

            if ($candle->getClose() < ($ema13[$key] - $atr[$key])) {
                $isDown = true;
                $T = -1;
            }

            if ($isUp) {

                $candle->setMeta(['lnl-trend-cloud' => 'bullish']);

            } else {

                $candle->setMeta(['lnl-trend-cloud' => 'bearish']);
            }

        });
    }
}
