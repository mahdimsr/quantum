<?php

namespace App\Listeners;

use App\Enums\OrderStatusEnum;
use App\Enums\StrategyEnum;
use App\Events\PendingOrderCreated;
use App\Models\Order;
use App\Services\Exchange\Enums\TypeEnum;
use App\Services\Exchange\Facade\Exchange;
use App\Services\Exchange\Repository\Target;
use App\Services\Order\Calculate;

class OpenDynamicRewardOrderListener
{
    public function __construct()
    {
        //
    }


    public function handle(PendingOrderCreated $event): void
    {
        if ($event->pendingOrder->strategy == StrategyEnum::DYNAMIC_REWARD) {

            $currentPrice = $event->pendingOrder->price;
            $balance = $event->pendingOrder->balance;

            Exchange::setLeverage(
                $event->pendingOrder->coin->symbol(),
                $event->pendingOrder->side,
                $event->pendingOrder->leverage
            );

            $quantity =  Calculate::quantity($balance, $currentPrice, $event->pendingOrder->leverage);

            $slTarget = Target::create(TypeEnum::STOP->value, $event->pendingOrder->sl, $event->pendingOrder->sl);

            $setOrderResponse = Exchange::setOrder(
                $event->pendingOrder->coin->symbol(),
                $event->pendingOrder->type,
                $event->pendingOrder->side,
                $event->pendingOrder->side,
                $quantity,
                $event->pendingOrder->price,
                $event->pendingOrder->client_id,
                null,
                $slTarget,
            );

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
}
