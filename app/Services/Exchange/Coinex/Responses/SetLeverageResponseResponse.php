<?php

namespace App\Services\Exchange\Coinex\Responses;

use App\Services\Exchange\Responses\SetLeverageResponseContract;

class SetLeverageResponseResponse extends ResponseAdapter implements SetLeverageResponseContract
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
