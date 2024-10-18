<?php

namespace App\Listeners;

use App\Events\OrderClosedEvent;
use App\Models\User;
use App\Notifications\OrderClosedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class OrderClosedNotifyListener
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
        Notification::send(User::mahdi(), new OrderClosedNotification($event->order));
    }
}
