<?php

namespace App\Services\Strategy;

use App\Services\Exchange\Repository\Candle;
use App\Services\Exchange\Repository\CandleCollection;
use App\Services\Indicator\Facade\Indicator;

class UTBotAlertCollection extends CandleCollection
{
    private CandleCollection $candleCollection;
    private int $sensitivity;
    private int $atrPeriod;

    public function __construct($items, int $sensitivity, int $atrPeriod)
    {
        parent::__construct($items);

        $this->candleCollection = CandleCollection::make($items);
        $this->sensitivity = $sensitivity;
        $this->atrPeriod = $atrPeriod;

        $this->calculateAverageTrueRange();

        $this->calculateATRTrailingStop();

//        $this->calculatePosition();

        $this->calculateCrossOver();

        $this->calculateSignal();
    }

    public function lastSignal(): Candle
    {
        return $this->candleCollection->filter(fn(Candle $candle) => array_key_exists('signal', $candle->getMeta()))->first();
    }

    private function calculateAverageTrueRange(): void
    {
        $highs = $this->candleCollection->highs()->toArray();
        $lows = $this->candleCollection->lows()->toArray();
        $closes = $this->candleCollection->closes()->toArray();

        $tr = Indicator::trueRange($highs, $lows, $closes);
        $atr = Indicator::averageTrueRange($highs, $lows, $closes, $this->atrPeriod);

        $this->candleCollection->each(fn(Candle $candle, $key) => $candle->setMeta(['atr' => $atr[$key], 'nLoss' => $this->sensitivity * $atr[$key]]));
    }

    private function calculateATRTrailingStop(): void
    {
        $trailingStop = [];


        for ($i = $this->candleCollection->count() - 1; $i >= 0 ; $i--) {

            $prevXATRTrailingStop = $trailingStop[$i + 1] ?? 0.0;

            $currentCandle = $this->candleCollection->get($i);
            $currentClose = $currentCandle->getClose();
            $currentNLoss = $currentCandle->getMeta()['nLoss'];

            $preCandle = $this->candleCollection->get($i + 1);
            $prevNLoss = $preCandle?->getMeta()['nLoss'] ?? 0.0;
            $preClose = $preCandle?->getClose();

            if ($currentClose > $prevXATRTrailingStop and $preClose > $prevXATRTrailingStop) {

                $trailingStop[$i] = max($prevXATRTrailingStop, $currentClose - $currentNLoss);

            } else if ($currentClose < $prevXATRTrailingStop and $preClose < $prevXATRTrailingStop) {

                $trailingStop[$i] = min($prevXATRTrailingStop, $currentClose + $currentNLoss);

            } else {

                $trailingStop[$i] = ($currentClose > $prevXATRTrailingStop) ? $currentClose - $currentNLoss : $currentClose + $currentNLoss;
            }
        }

        $trailingStop = array_reverse($trailingStop);

        $this->candleCollection->each(fn(Candle $candle, $key) => $candle-> setMeta(['trailing-stop' => $trailingStop[$key]]));
    }

    private function calculatePosition(): void
    {
        for ($i = 0; $i < $this->candleCollection->count() - 1; $i++) {

            $currentCandle = $this->candleCollection->get($i);
            $currentClose = $currentCandle->getClose();

            $preCandle = $this->candleCollection->get($i + 1);
            $preClose = $preCandle->getClose();
            $preTrailingStop = $preCandle->getMeta()['trailing-stop'];

            if ($preClose < $preTrailingStop and $currentClose > $preTrailingStop) {

                $currentCandle->setMeta(['position' => 1]);
            }

            if ($preClose > $preTrailingStop and $currentClose < $preTrailingStop) {

                $currentCandle->setMeta(['position' => -1]);
            }
        }
    }

    private function calculateCrossOver(): void
    {
        $ema1 = Indicator::EMA($this->candleCollection, 1);

        $this->candleCollection->map(fn(Candle $candle, $key) => $candle->setMeta(['ema-1' => $ema1[$key]]));

        for ($i = 0; $i < $this->candleCollection->count() -1 ; $i++) {

            $currentCandle = $this->candleCollection->get($i);

            $preCandle = $this->candleCollection->get($i + 1);

            if ($currentCandle->getMeta()['ema-1'] > $currentCandle->getMeta()['trailing-stop'] and $preCandle->getMeta()['ema-1'] < $preCandle->getMeta()['trailing-stop']) {

                $currentCandle->setMeta(['cross-over' => 'above']);

            } elseif ($currentCandle->getMeta()['ema-1'] < $currentCandle->getMeta()['trailing-stop'] and $preCandle->getMeta()['ema-1'] > $preCandle->getMeta()['trailing-stop']) {

                $currentCandle->setMeta(['cross-over' => 'below']);
            }
        }
    }

    private function crossOvers(): CandleCollection
    {
        return $this->candleCollection->filter(fn(Candle $candle) => array_key_exists('cross-over', $candle->getMeta()));
    }

    private function calculateSignal(): void
    {
        $this->crossOvers()->map(function (Candle $candle) {

            $close = $candle->getClose();
            $trailingStop = $candle->getMeta()['trailing-stop'];
            $crossOver = $candle->getMeta()['cross-over'];

            if ($close > $trailingStop and $crossOver == 'above') {

                $candle->setMeta(['signal' => 'buy']);
            }

            if ($close < $trailingStop and $crossOver == 'below') {

                $candle->setMeta(['signal' => 'sell']);
            }

        });
    }
}
