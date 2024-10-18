<?php

namespace App\Services\Exchange\BingX\Response;

use App\Services\Exchange\Repository\Order;
use App\Services\Exchange\Responses\SetOrderResponseContract;

class SetOrderResponseAdapter implements SetOrderResponseContract
{
    protected array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function isSuccess(): bool
    {
        return $this->data['code'] == 0;
    }

    public function message(): string
    {
        return $this->data['msg'];
    }

    public function order(): ?Order
    {
        $orderResponse = $this->data['data']['order'];

        return Order::create(
            $orderResponse['symbol'],
            $orderResponse['side'],
            $orderResponse['type'],
            $orderResponse['price'],
            $orderResponse['quantity'],
            $orderResponse['clientOrderID'],
            $orderResponse['orderID'],
        );
    }
}
