<?php

namespace App\Services\Strategy;

use App\Services\Exchange\Repository\Candle;
use App\Services\Exchange\Repository\CandleCollection;
use App\Services\Indicator\Technical\BollingerBands;

class BollingerBandStrategy
{
    protected CandleCollection $candles;
    protected array $bollingerBandsData;

    private array $lastBollingerbands;
    private Candle $lastCandle;

    public function __construct(CandleCollection $candles)
    {
        $this->candles = $candles;
        $bollingerBandsData = (new BollingerBands($candles))->run();

        $this->lastBollingerbands = collect($bollingerBandsData)->last();
        $this->lastCandle = $candles->lastCandle();
    }

    protected function hasTouchedUpperBand(): bool
    {
        $upperBand = $this->lastBollingerbands['upper_band'];

        return $this->lastCandle->getHigh() >= $upperBand;
    }

    protected function hasTouchedLowerBand(): bool
    {
        $lowerBand = $this->lastBollingerbands['lower_band'];

        return $this->lastCandle->getLow() <= $lowerBand;
    }

    public function buy(): bool
    {
        return $this->hasTouchedLowerBand() and $this->lastCandle->isBullish();
    }

    public function sell(): bool
    {
        return $this->hasTouchedUpperBand() and ! $this->lastCandle->isBullish();
    }
}
