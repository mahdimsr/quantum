<?php

namespace App\Services\Exchange\Coinex\Responses;

use App\Services\Exchange\Responses\AdjustPositionLeverageContract;

class AdjustPositionLeverageResponse extends ResponseAdapter implements AdjustPositionLeverageContract
{
    public function margin_mode(): string
    {
        return $this->data()['margin_mode'];
    }

    public function leverage(): int
    {
        return $this->data()['leverage'];
    }
}
