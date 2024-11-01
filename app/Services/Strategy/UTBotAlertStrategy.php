<?php

namespace App\Services\Strategy;

use App\Services\Exchange\Repository\Candle;
use App\Services\Exchange\Repository\CandleCollection;
use App\Services\Indicator\Facade\Indicator;
use Illuminate\Support\Str;

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

    private function signalExists(int $candleIndex, string $signal): bool
    {
        $candle = $this->collection()->get($candleIndex);

        if ($candle and array_key_exists('signal', $candle->getMeta())) {


            $lowerSignal = Str::lower($signal);
            $lowerCandleSignal = Str::lower($candle->getMeta()['signal']);

            return $lowerCandleSignal == $lowerSignal;
        }

        return false;
    }

    public function lastSignalCandle(): Candle
    {
        return $this->UTBotAlertCollection->lastSignal();
    }

    public function isBullish(): bool
    {
        return $this->lastSignalCandle()->getMeta()['signal'] == 'buy';
    }

    public function buySignal(int $candleIndex = 0): bool
    {
        return $this->signalExists($candleIndex, 'buy');
    }

    public function isBearish(): bool
    {
        return $this->lastSignalCandle()->getMeta()['signal'] == 'sell';
    }

    public function sellSignal(int $candleIndex = 0): bool
    {
        return $this->signalExists($candleIndex, 'sell');
    }

    public function currentPrice(): mixed
    {
        return $this->collection()->get(0)->getClose();
    }

}
