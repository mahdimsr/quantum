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

    public function isBuy(): bool
    {
        return $this->lastSignalCandle()->getMeta()['signal'] == 'buy';
    }

    public function isSell(): bool
    {
        return $this->lastSignalCandle()->getMeta()['signal'] == 'sell';
    }

}
