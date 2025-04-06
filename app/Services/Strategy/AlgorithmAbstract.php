<?php

namespace App\Services\Strategy;

use App\Enums\PositionTypeEnum;
use App\Services\Exchange\Repository\CandleCollection;

abstract class AlgorithmAbstract
{
    public function __construct(
        protected CandleCollection $candleCollection,
    )
    {
    }

    public function handle(\Closure $next): CandleCollection
    {
        return $next($this->candleCollection);
    }

    abstract public function signal(): ?PositionTypeEnum;
}
