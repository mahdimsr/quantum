<?php

namespace App\Services\Strategy;

use App\Services\Exchange\Repository\Candle;
use App\Services\Exchange\Repository\CandleCollection;
use App\Services\Indicator\Facade\Indicator;
use Illuminate\Support\Collection;

class LNLTrendCollection extends CandleCollection
{
    private CandleCollection $candleCollection;

    public function __construct($items = [])
    {
        parent::__construct($items);

        $this->candleCollection = $items;

        $this->calculateTrend();
    }

    private function calculateTrend(): void
    {
        $ema8  = Indicator::EMA($this->candleCollection, 8);
        $ema13 = Indicator::EMA($this->candleCollection, 13);
        $ema21 = Indicator::EMA($this->candleCollection, 21);
        $ema34 = Indicator::EMA($this->candleCollection, 34);

        $this->candleCollection->map(function (Candle $candle, $key) use ($ema8, $ema13, $ema21, $ema34) {

            $bullish = $ema8[$key] > $ema13[$key] and $ema13[$key] > $ema21[$key] and $ema21[$key] > $ema34[$key];
            $bearish = $ema8[$key] < $ema13[$key] and $ema13[$key] < $ema21[$key] and $ema21[$key] < $ema34[$key];

            $trend = 'normal';

            if ($bullish) {
                $trend = 'bullish';
            } else if ($bearish) {
                $trend = 'bearish';
            }

            $candle->setMeta(['lnl-trend' => $trend]);
        });
    }
}
