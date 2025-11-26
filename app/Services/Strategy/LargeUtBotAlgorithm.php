<?php

namespace App\Services\Strategy;

use App\Enums\PositionTypeEnum;
use App\Services\Exchange\Repository\CandleCollection;
use App\Services\Indicator\Strategy\UTBotAlertStrategy;

class LargeUtBotAlgorithm extends AlgorithmAbstract
{
    private UTBotAlertStrategy $utBotAlertLarge;
    private string $groupName;

    public function __construct(CandleCollection $candleCollection)
    {
        parent::__construct($candleCollection);
        $this->groupName = 'large';


        $this->utBotAlertLarge = new UTBotAlertStrategy($this->candleCollection, 2, 3, $this->groupName);
    }

    public function signal(): ?PositionTypeEnum
    {
        if ($this->utBotAlertLarge->isBearish()) {
            return PositionTypeEnum::SHORT;
        }

        if ($this->utBotAlertLarge->isBullish()) {
            return PositionTypeEnum::LONG;
        }

        return null;
    }
}
