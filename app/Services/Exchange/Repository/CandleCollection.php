<?php

namespace App\Services\Exchange\Repository;

use Illuminate\Support\Collection;

class CandleCollection extends Collection
{
    public function highs(): Collection
    {
        return $this->map(fn(Candle $candle) => $candle->getHigh());
    }

    public function maxHigh(): mixed
    {
        return $this->highs()->max();
    }

    public function lows(): Collection
    {
        return $this->map(fn(Candle $candle) => $candle->getLow());
    }

    public function minLow(): mixed
    {
        return $this->lows()->min();
    }

    public function closes(): Collection
    {
        return $this->map(fn(Candle $candle) => $candle->getClose());
    }
}
