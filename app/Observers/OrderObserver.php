<?php

namespace App\Observers;

use App\Models\Order;

class OrderObserver
{
    public function creating(Order $order): void
    {
        if (! $order->coin) {

            $order->coin = explode('-', $order->symbol)[0];
        }

        if (! $order->client_id) {

            $order->client_id = $order->coin . now()->timestamp;
        }
    }
}
