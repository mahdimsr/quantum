<?php

namespace App\Services\Exchange\Coinex\Responses;

use App\Services\Exchange\Repository\Order;
use App\Services\Exchange\Responses\SetOrderResponseContract;

class SetOrderResponseAdapter extends ResponseAdapter implements SetOrderResponseContract
{

    public function order(): ?Order
    {
        return Order::fromArray($this->data());
    }
}
