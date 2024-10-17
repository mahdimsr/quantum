<?php

namespace App\Listeners;

use App\Enums\OrderStatusEnum;
use App\Events\PendingOrderCreated;
use App\Services\Exchange\Bingx\BingXService;
use App\Services\Exchange\Enums\TypeEnum;
use App\Services\Exchange\Repository\Target;
use App\Services\Order\Calculate;

class BingXOpenOrderListener
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
        $balance = 19;
        $leverage = 5;

        $asset = $balance * $currentPrice;
        $amount = Calculate::maxOrderAmount($currentPrice, $asset, $leverage);

        $tpPrice = Calculate::target($currentPrice, 1);
        $slPrice = Calculate::target($tpPrice, -2);

        $tpTarget = Target::create(TypeEnum::TAKE_PROFIT->value, $tpPrice, $tpPrice);
        $slTarget = Target::create(TypeEnum::STOP->value, $slPrice, $slPrice);

        $bingxService = app(BingXService::class);

        $setOrderResponse = $bingxService->setOrder(
            $event->pendingOrder->coin->symbol('-'),
            $event->pendingOrder->type,
            $event->pendingOrder->side,
            $event->pendingOrder->side,
            $amount,
            $currentPrice,
            $event->pendingOrder->client_id,
            $tpTarget,
            $slTarget,
        );

        if ($setOrderResponse->isSuccess()) {

            $event->pendingOrder->update([
                'status' => OrderStatusEnum::PENDING,
                'exchange_order_id' => $setOrderResponse->order()->getOrderId()
            ]);
        }
    }
}
