<?php

namespace App\Observers;

use App\Models\Order;
use Illuminate\Support\Str;

class OrderObserver
{
    public function creating(Order $order): void
    {
        if (! $order->coin_name) {

            $order->coin_name = explode('-', $order->symbol)[0];
        }

        if (! $order->client_id) {

            $order->client_id = $order->coin_name .'-'. now()->timestamp;
        }

        $order->side = Str::of($order->side->value)->upper()->toString();
        $order->type = Str::of($order->type->value)->upper()->toString();
        $order->status = Str::of($order->status->value)->upper()->toString();
    }
}
