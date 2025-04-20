<?php

namespace App\Console\Commands;

use App\Enums\OrderStatusEnum;
use App\Models\Order;
use App\Services\Exchange\Facade\Exchange;
use Illuminate\Console\Command;

class UpdateOrderPositionIdCommand extends Command
{
    protected $signature = 'app:update-order-position-id';

    protected $description = 'This command updates order position id or what other action do you think dummy?';

    public function handle()
    {
        $orders = Order::query()
            ->whereNull('position_id')
            ->where('status', '!=', OrderStatusEnum::UNKNOWN)
            ->get();

        foreach ($orders as $order) {
            $currentPositionResponse = Exchange::currentPosition($order->coin->symbol());

            if ($currentPositionResponse->isSuccess() and $currentPositionResponse->position()) {

                $order->update([
                    'position_id' => $currentPositionResponse->position()->getPositionId()
                ]);

            } else {

                $order->update([
                    'status' => OrderStatusEnum::UNKNOWN,
                ]);
            }
        }
    }
}
