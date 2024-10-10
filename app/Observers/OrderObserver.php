<?php

namespace App\Observers;

use App\Models\Order;
use Illuminate\Support\Str;

class OrderObserver
{
    public function creating(Order $order): void
    {
        if (! $order->coin) {

            $order->coin = explode('-', $order->symbol)[0];
        }

        if (! $order->client_id) {

            $order->client_id = $order->coin .'-'. now()->timestamp;
        }

        $order->side = Str::of($order->side)->upper()->toString();
        $order->type = Str::of($order->type)->upper()->toString();
        $order->status = Str::of($order->status)->upper()->toString();
    }
}
