<?php

namespace App\Providers;

use App\Events\OrderClosedEvent;
use App\Events\PendingOrderCreated;
use App\Listeners\OpenDynamicRewardOrderListener;
use App\Listeners\OpenStaticRewardOrderListener;
use App\Listeners\ChangeClosedOrderStatusListener;
use App\Listeners\OrderClosedNotifyListener;
use App\Listeners\OrderOpenedNotifyListener;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],

        PendingOrderCreated::class => [
            OpenStaticRewardOrderListener::class,
            OpenDynamicRewardOrderListener::class,
            OrderOpenedNotifyListener::class,
        ],

        OrderClosedEvent::class => [
            ChangeClosedOrderStatusListener::class,
            OrderClosedNotifyListener::class,
        ]
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
