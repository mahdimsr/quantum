<?php

namespace App\Services\Exchange\Coinex\Responses;

use App\Services\Exchange\Repository\Order;
use App\Services\Exchange\Responses\SetOrderResponseContract;

class OrderResponseAdapter extends BaseResponse implements SetOrderResponseContract
{

    public function order(): ?Order
    {
        $data = $this->response['data'];

        return Order::create(
            $data['market'],
            $data['side'],
            $data['type'],
            $data['price'],
            $data['amount'],
            $data['client_id'],
            $data['order_id'],
        );
    }
}
