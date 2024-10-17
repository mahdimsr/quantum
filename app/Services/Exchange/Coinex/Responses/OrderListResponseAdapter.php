<?php

namespace App\Services\Exchange\Coinex\Responses;

use App\Services\Exchange\Repository\Order;
use App\Services\Exchange\Repository\OrderCollection;
use App\Services\Exchange\Responses\OrderListResponseContract;

class OrderListResponseAdapter implements OrderListResponseContract
{

    private array $response;

    public function __construct(array $response)
    {
        $this->response = $response;
    }

    public function code(): int
    {
        return $this->response['code'];

    }

    public function message(): string
    {
        return $this->response['message'];

    }

    public function data(): OrderCollection
    {
        $data = $this->response['data'];

        $data = collect($data)->map(fn($item) => Order::create($item['symbol'], $item['side'], $item['type'], $item['price'],$item['origQty'],$item['clientOrderId']));

        return OrderCollection::make($data);
    }
}
