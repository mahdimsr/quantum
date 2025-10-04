<?php

namespace App\Services\Exchange\Bitunix\Responses;

use App\Services\Exchange\Repository\Order;
use App\Services\Exchange\Responses\SetOrderResponseContract;

class OrderResponseAdapter extends BaseResponse implements SetOrderResponseContract
{

    public function order(): ?Order
    {
        $data = $this->response['data'];

        return Order::create(
            $data['symbol'] ?? null,
            $data['side'] ?? null,
            $data['orderType'] ?? null,
            $data['price'] ?? 0,
            $data['qty'] ?? null,
            $data['clientId'] ?? null,
            $data['orderId'] ?? null,
        );
    }
}
