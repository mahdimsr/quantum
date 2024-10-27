<?php

namespace App\Services\Strategy;

use App\Services\Exchange\Repository\Candle;
use App\Services\Exchange\Repository\CandleCollection;
use App\Services\Indicator\Facade\Indicator;

class UTBotAlertStrategy
{
    private CandleCollection $candles;
    private ?UTBotAlertCollection $UTBotAlertCollection;
    private int $sensitivity;
    private int $atrPeriod;
    private array $nLoss;

    protected array $closeValues;
    protected array $highValues;
    protected array $lowsValues;

    protected array $ema;
    protected array $ATRTrailingStop;

    public function __construct(CandleCollection $candles, int $sensitivity = 1, int $atrPeriod = 10)
    {
        $this->candles = $candles;
        $this->sensitivity = $sensitivity;
        $this->atrPeriod = $atrPeriod;

        $this->UTBotAlertCollection = $this->collection();
    }

    public function collection(): ?UTBotAlertCollection
    {
        if (isset($this->UTBotAlertCollection) and $this->UTBotAlertCollection) {

            return $this->UTBotAlertCollection;
        }

        return new UTBotAlertCollection($this->candles, $this->sensitivity, $this->atrPeriod);
    }

    public function lastSignalCandle(): Candle
    {
        return $this->UTBotAlertCollection->lastSignal();
    }

    public function signalOfRecentCandles(int $index = 3): ?Candle
    {
        return $this->UTBotAlertCollection->recentSignal($index);
    }

    public function isBuy(?int $recentCandles = null): bool
    {
        if ($recentCandles) {

            $recentSignal = $this->signalOfRecentCandles($recentCandles);

            if ($recentSignal) {

                return $recentSignal->getMeta()['signal'] == 'buy';
            }

            return false;
        }

        return $this->lastSignalCandle()->getMeta()['signal'] == 'buy';
    }

    public function isSell(?int $recentCandles = null): bool
    {
        if ($recentCandles) {

            $recentSignal = $this->signalOfRecentCandles($recentCandles);

            if ($recentSignal) {

                return $recentSignal->getMeta()['signal'] == 'sell';
            }

            return false;
        }

        return $this->lastSignalCandle()->getMeta()['signal'] == 'sell';
    }

    public function currentPrice(): mixed
    {
        return $this->collection()->get(0)->getClose();
    }

}
