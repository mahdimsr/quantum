<?php

namespace App\Listeners;

use App\Events\PendingOrderCreated;
use App\Models\User;
use App\Notifications\OrderOpenedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class OrderOpenedNotifyListener
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
    public function handle(PendingOrderCreated $event): void
    {
        Notification::send(User::mahdi(), new OrderOpenedNotification($event->pendingOrder));
    }
}
