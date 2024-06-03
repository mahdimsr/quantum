<?php

namespace App\Services\Strategy;

use App\Services\Exchange\Repository\Candle;
use App\Services\Exchange\Repository\CandleCollection;
use App\Services\Indicator\Facade\Indicator;

class UTBotAlertStrategy
{
    private CandleCollection $candles;
    private array $nLoss;

    protected array $closeValues;
    protected array $highValues;
    protected array $lowsValues;

    protected array $ema;
    protected array $ATRTrailingStop;

    public function __construct(CandleCollection $candles, int $multiplier = 1)
    {
        $this->candles = $candles;

        $this->closeValues = $candles->closes()->toArray();
        $this->highValues = $candles->highs()->toArray();
        $this->lowsValues = $candles->lows()->toArray();

        $atr = Indicator::averageTrueRange($this->highValues, $this->lowsValues, $this->closeValues, 10);

        $this->nLoss = collect($atr)->map(fn($atrValue) => $atrValue * 1)->toArray();


        $this->ATRTrailingStop = $this->calculateATRTrailingStop();
        $this->calculatePosition($this->closeValues, $this->ATRTrailingStop);
        $this->calculateCrossOvers();
        $this->calculateOrderType();
    }

    public function calculateATRTrailingStop(): array
    {
        $xATRTrailingStop = array_fill(0, count($this->closeValues), 0.0);

        for ($i = 1; $i < count($this->closeValues); $i++) {
            // Use the previous value of xATRTrailingStop or 0 if it was not set
            $prevXATRTrailingStop = $xATRTrailingStop[$i - 1] ?? 0.0;
            $prevNLoss = $this->nLoss[$i - 1] ?? 0.0;

            if ($this->closeValues[$i] > $prevXATRTrailingStop && $this->closeValues[$i - 1] > $prevXATRTrailingStop) {
                $xATRTrailingStop[$i] = max($prevXATRTrailingStop, $this->closeValues[$i] - $prevNLoss);
            } elseif ($this->closeValues[$i] < $prevXATRTrailingStop && $this->closeValues[$i - 1] < $prevXATRTrailingStop) {
                $xATRTrailingStop[$i] = min($prevXATRTrailingStop, $this->closeValues[$i] + $prevNLoss);
            } else {
                $xATRTrailingStop[$i] = ($this->closeValues[$i] > $prevXATRTrailingStop) ? $this->closeValues[$i] - $prevNLoss : $this->closeValues[$i] + $prevNLoss;
            }
        }

        $this->candles = $this->candles->mergeDataInMeta($xATRTrailingStop, 'atr');

        return $xATRTrailingStop;
    }

    public function calculatePosition(array $src, array $xATRTrailingStop): array
    {
        $pos = [];
        $pos[0] = 0; // Initialize the first position to 0

        for ($i = 1; $i < count($src); $i++) {
            $prevSrc = $src[$i - 1];
            $prevXATRTrailingStop = $xATRTrailingStop[$i - 1] ?? 0;
            $prevPos = $pos[$i - 1] ?? 0;

            if ($prevSrc < $prevXATRTrailingStop && $src[$i] > $prevXATRTrailingStop) {
                $pos[$i] = 1;
            } elseif ($prevSrc > $prevXATRTrailingStop && $src[$i] < $prevXATRTrailingStop) {
                $pos[$i] = -1;
            } else {
                $pos[$i] = $prevPos;
            }

        }

        $this->candles = $this->candles->mergeDataInMeta($pos, 'pos');

        return $pos;
    }

    public function calculateEMA(): void
    {
        $this->ema = Indicator::EMA($this->candles, 2);

        $this->candles = $this->candles->mergeDataInMeta($this->ema, 'ema');
    }

    public function calculateCrossOvers(): void
    {
        $this->calculateEMA();

        $aboveCrossOver = Indicator::crossover($this->ema, $this->ATRTrailingStop);

        $aboveCrossOver = collect($aboveCrossOver)->map(fn($value) => !$value ? 'below' : 'above')->all();

        $this->candles = $this->candles->mergeDataInMeta($aboveCrossOver, 'cross');
    }

    public function calculateOrderType(): void
    {
        $this->candles = $this->candles->each(function (Candle $candle, int $index) {

            $meta = $candle->getMeta();

            $additionalMeta = [];

            if ($candle->getClose() > $meta['atr'] and $meta['cross'] == 'above') {

                $additionalMeta = ['order' => 'buy'];

            }

            if ($candle->getClose() < $meta['atr'] and $meta['cross'] == 'below') {

                $additionalMeta = ['order' => 'sell'];
            }

            if (count($additionalMeta) == 0 and $index != 0) {

                $preCandle = $this->candles->toArray()[$index - 1];

                $preOrder = array_key_exists('order', $preCandle->getMeta()) ? $preCandle->getMeta()['order'] : 'not defined';

                $additionalMeta = ['order' => $preOrder];
            }

            $candle->setMeta($additionalMeta);
        });
    }

    public function getCalculatedCandles(): CandleCollection
    {
        return $this->candles;
    }

    public function lastPosition(): Candle
    {
        return $this->candles->filter(fn(Candle $candle) => array_key_exists('order', $candle->getMeta()) and in_array($candle->getMeta()['order'], ['buy', 'sell']))->last();
    }
}
