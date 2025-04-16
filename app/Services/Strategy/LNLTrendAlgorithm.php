<?php

namespace App\Services\Strategy;

use App\Enums\PositionTypeEnum;
use App\Services\Exchange\Repository\CandleCollection;
use App\Services\Indicator\Strategy\LNLTrendStrategy;

class LNLTrendAlgorithm extends AlgorithmAbstract
{
    private LNLTrendStrategy $LNLTrendStrategy;

    public function __construct(CandleCollection $candleCollection)
    {
        parent::__construct($candleCollection);

        $this->LNLTrendStrategy = new LNLTrendStrategy($candleCollection);
    }

    public function signal(): ?PositionTypeEnum
    {
        if ($this->LNLTrendStrategy->isBearish()){
            return PositionTypeEnum::SHORT;
        }

        if ($this->LNLTrendStrategy->isBullish()) {
            return PositionTypeEnum::LONG;
        }

        return null;
    }
}
