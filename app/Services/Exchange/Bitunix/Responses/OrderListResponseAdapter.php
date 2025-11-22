<?php

namespace App\Services\Exchange\Bitunix\Responses;

use App\Services\Exchange\Repository\Order;
use App\Services\Exchange\Repository\OrderCollection;
use App\Services\Exchange\Responses\OrderListResponseContract;

class OrderListResponseAdapter extends BaseResponse implements OrderListResponseContract
{

    public function data(): OrderCollection
    {
        $data = $this->response['data'];
        
        $orders = collect($data)->map(function ($item) {
            return Order::create(
                $item['symbol'],
                $item['side'],
                $item['orderType'],
                $item['price'] ?? 0,
                $item['qty'],
                $item['clientId'] ?? null,
                $item['orderId'] ?? null,
            );
        });

        return OrderCollection::make($orders);
    }
}
