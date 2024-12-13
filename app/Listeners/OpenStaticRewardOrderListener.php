<?php

namespace App\Listeners;

use App\Enums\OrderStatusEnum;
use App\Enums\StrategyEnum;
use App\Events\PendingOrderCreated;
use App\Services\Exchange\BingX\BingXService;
use App\Services\Exchange\Enums\TypeEnum;
use App\Services\Exchange\Facade\Exchange;
use App\Services\Exchange\Repository\Target;
use App\Services\Order\Calculate;

class OpenStaticRewardOrderListener
{
    public function handle(PendingOrderCreated $event): void
    {
        if ($event->pendingOrder->strategy->name == StrategyEnum::Static_Profit) {

            $currentPrice = $event->pendingOrder->price;
            $balance = $event->pendingOrder->balance;

            Exchange::setLeverage(
                $event->pendingOrder->coin->symbol('-'),
                $event->pendingOrder->side,
                $event->pendingOrder->leverage,
            );


            $quantity =  Calculate::quantity($balance, $currentPrice, $event->pendingOrder->leverage);

            $tpTarget = Target::create(TypeEnum::TAKE_PROFIT->value, $event->pendingOrder->tp, $event->pendingOrder->tp);
            $slTarget = Target::create(TypeEnum::STOP->value, $event->pendingOrder->sl, $event->pendingOrder->sl);

            $setOrderResponse = Exchange::setOrder(
                $event->pendingOrder->coin->symbol('-'),
                $event->pendingOrder->type,
                $event->pendingOrder->side,
                $event->pendingOrder->side,
                $quantity,
                $event->pendingOrder->price,
                $event->pendingOrder->client_id,
                $tpTarget,
                $slTarget,
            );

            if ($setOrderResponse->isSuccess()) {

                $event->pendingOrder->update([
                    'status' => OrderStatusEnum::OPEN,
                    'exchange_order_id' => $setOrderResponse->order()->getOrderId(),
                    'balance' => $balance,
                ]);

            } else {

                $event->pendingOrder->delete();
            }
        }
    }
}
