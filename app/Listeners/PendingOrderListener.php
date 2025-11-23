<?php

namespace App\Listeners;

use App\Enums\OrderStatusEnum;
use App\Events\PendingOrderCreated;
use App\Services\Exchange\Enums\TypeEnum;
use App\Services\Exchange\Facade\Exchange;
use App\Services\Exchange\Repository\Target;
use App\Services\Order\Calculate;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class PendingOrderListener
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


        $currentPrice = $event->pendingOrder->price;
        $balance = $event->pendingOrder->balance;

        Exchange::setLeverage(
            $event->pendingOrder->coin->symbol(),
            $event->pendingOrder->side,
            $event->pendingOrder->leverage
        );

        $quantity = Calculate::quantity($balance, $currentPrice, $event->pendingOrder->leverage);

        $slTarget = Target::create(TypeEnum::STOP->value, $event->pendingOrder->sl, $event->pendingOrder->sl);
        $tpTarget = null;

        if ($event->pendingOrder->tp) {
            $tpTarget = Target::create(TypeEnum::TAKE_PROFIT->value, $event->pendingOrder->tp, $event->pendingOrder->tp);
        }

        $setOrderResponse = Exchange::setOrder(
            $event->pendingOrder->coin->symbol(),
            $event->pendingOrder->type,
            $event->pendingOrder->side,
            $event->pendingOrder->side,
            $quantity,
            $event->pendingOrder->price,
            $event->pendingOrder->client_id,
            $tpTarget,
            $slTarget,
        );

        logs()->error('RESPONSE IS SUCCESS: ' . $setOrderResponse->isSuccess());
        logs()->error('RESPONSE MESSAGE: ' . $setOrderResponse->message());

        if ($setOrderResponse->isSuccess()) {

            $event->pendingOrder->update([
                'status' => OrderStatusEnum::OPEN,
                'exchange_order_id' => $setOrderResponse->order()->getOrderId()
            ]);

        } else {

            $event->pendingOrder->update([
                'status' => OrderStatusEnum::FAILED,
            ]);
        }

    }
}
