<?php

namespace App\Services\Strategy;

use App\Services\Exchange\Repository\CandleCollection;

interface StrategyContract
{
    public function isBullish(): bool;
    public function isBearish(): bool;
    public function sellSignal(?int $candleIndex = 0): bool;
    public function buySignal(?int $candleIndex = 0): bool;

    public function currentPrice(): mixed;

    public function collection(): CandleCollection;
}
