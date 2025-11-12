<?php

namespace App\Services\Strategy;

use App\Enums\PositionTypeEnum;
use App\Services\Exchange\Repository\CandleCollection;
use App\Services\Indicator\Strategy\UTBotAlertStrategy;

class SmallUtBotAlgorithm extends AlgorithmAbstract
{
    private UTBotAlertStrategy $utBotAlertSmall;
    private string $groupName;

    public function __construct(CandleCollection $candleCollection)
    {
        parent::__construct($candleCollection);
        $this->groupName = 'small';


        $this->utBotAlertSmall = new UTBotAlertStrategy($this->candleCollection, 1, 2, $this->groupName);
    }

    public function signal(): ?PositionTypeEnum
    {
        if ($this->utBotAlertSmall->sellSignal(1)) {
            return PositionTypeEnum::SHORT;
        }

        if ($this->utBotAlertSmall->buySignal(1)) {
            return PositionTypeEnum::LONG;
        }

        return null;
    }
}
