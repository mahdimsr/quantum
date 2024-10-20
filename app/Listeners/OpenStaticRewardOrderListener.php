<?php

namespace App\Listeners;

use App\Enums\OrderStatusEnum;
use App\Events\PendingOrderCreated;
use App\Services\Exchange\BingX\BingXService;
use App\Services\Exchange\Enums\TypeEnum;
use App\Services\Exchange\Repository\Target;
use App\Services\Order\Calculate;

class OpenStaticRewardOrderListener
{
    private BingXService $bingXService;
    private mixed $balance;

    public function __construct()
    {
        $this->bingXService = app(BingXService::class);
        $exchangeBalance = $this->bingXService->futuresBalance()->balance();
        $this->balance = round($exchangeBalance, 2);
    }

    /**
     * Handle the event.
     */
    public function handle(PendingOrderCreated $event): void
    {
        $currentPrice = $event->pendingOrder->price;

        $this->bingXService->setLeverage(
            $event->pendingOrder->coin->symbol('-'),
            $event->pendingOrder->side,
            $event->pendingOrder->leverage,
        );


        $quantity =  Calculate::quantity($this->balance, $currentPrice, $event->pendingOrder->leverage);

        if ($quantity < 1) {

            $quantity = round($quantity, 4, PHP_ROUND_HALF_DOWN);

        } else {

            $quantity = round($quantity, 1, PHP_ROUND_HALF_DOWN) - 1;
        }

        $tpTarget = Target::create(TypeEnum::TAKE_PROFIT->value, $event->pendingOrder->tp, $event->pendingOrder->tp);
        $slTarget = Target::create(TypeEnum::STOP->value, $event->pendingOrder->sl, $event->pendingOrder->sl);

        $setOrderResponse = $this->bingXService->setOrder(
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
                'status' => OrderStatusEnum::PENDING,
                'exchange_order_id' => $setOrderResponse->order()->getOrderId(),
                'balance' => $this->balance
            ]);

        } else {

            $event->pendingOrder->forceDelete();
        }
    }
}
