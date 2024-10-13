<?php

namespace App\Services\Exchange\Repository;

use Illuminate\Support\Collection;

class CandleCollection extends Collection
{
    public function highs(): Collection
    {
        return $this->map(fn(Candle $candle) => $candle->getHigh())->reverse()->values();
    }

    public function maxHigh(): mixed
    {
        return $this->highs()->max();
    }

    public function lows(): Collection
    {
        return $this->map(fn(Candle $candle) => $candle->getLow())->reverse()->values();
    }

    public function minLow(): mixed
    {
        return $this->lows()->min();
    }

    public function closes(): Collection
    {
        return $this->map(fn(Candle $candle) => $candle->getClose())->reverse()->values();
    }

    public function lastCandle(): Candle
    {
        return $this->last();
    }

    public function mergeDataInMeta(array $data, $key): CandleCollection
    {
        return $this->each(fn(Candle $candle, $index) => $candle->setMeta([$key => $data[$index]]));
    }
}
