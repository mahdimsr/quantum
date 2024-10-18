<?php

namespace App\Services\Strategy;

use App\Services\Exchange\Repository\CandleCollection;
use App\Services\Indicator\Facade\Indicator;

class LNLTrendStrategy
{
    private CandleCollection $candleCollection;
    private ?LNLTrendCollection $LNLTrendCollection;

    public function __construct(CandleCollection $candleCollection)
    {
        $this->candleCollection = $candleCollection;

        $this->LNLTrendCollection = $this->collection();
    }

    public function collection(): LNLTrendCollection
    {
        if (isset($this->LNLTrendCollection) and $this->LNLTrendCollection) {

            return $this->LNLTrendCollection;
        }

        return new LNLTrendCollection($this->candleCollection);
    }

    public function currentTrend(): string
    {
        return $this->collection()->currentTrendCloud();
    }

    public function isPowerTrend(): bool
    {
        return $this->collection()->currentTrendCloud() == $this->collection()->currentTrendLine();
    }

    public function isBullish(): bool
    {
        return $this->currentTrend() == 'bullish';
    }

    public function isBearish(): bool
    {
        return $this->currentTrend() == 'bearish';
    }
}
