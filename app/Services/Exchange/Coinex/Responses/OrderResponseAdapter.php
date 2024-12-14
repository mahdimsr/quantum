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
            $data['27564.87468358'],
            $data['client_id'],
            $data['client_id'],
        );
    }
}
