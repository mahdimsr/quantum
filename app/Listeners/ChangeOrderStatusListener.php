<?php

namespace App\Listeners;

use App\Enums\OrderStatusEnum;
use App\Events\OrderClosedEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ChangeOrderStatusListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(OrderClosedEvent $event): void
    {
        $event->order->update([
           'status' => OrderStatusEnum::DONE
        ]);
    }
}
