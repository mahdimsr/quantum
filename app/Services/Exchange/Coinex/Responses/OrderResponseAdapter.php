<?php

namespace App\Services\Exchange\Coinex\Responses;

use App\Services\Exchange\Repository\Order;
use App\Services\Exchange\Responses\OrderResponseContract;

class OrderResponseAdapter extends ResponseAdapter implements OrderResponseContract
{

    public function order(): ?Order
    {
        return Order::fromArray($this->data());
    }
}
