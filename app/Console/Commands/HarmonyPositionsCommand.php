<?php

namespace App\Console\Commands;

use App\Enums\OrderStatusEnum;
use App\Models\Order;
use App\Services\Exchange\Facade\Exchange;
use App\Services\Strategy\Defaults\HarmonyStrategy;
use Illuminate\Console\Command;

class HarmonyPositionsCommand extends Command
{
    protected $signature = 'app:harmony-positions';

    protected $description = 'It check realized pnl of harmony position and add it to margin';

    public function handle(): int
    {
        $harmonyStrategy = app(HarmonyStrategy::class);

        $orders = Order::query()
            ->where('strategy', $harmonyStrategy->name())
            ->where('status', OrderStatusEnum::HAS_TP)
            ->get();

        if ($orders->isEmpty()){
            $this->info('There are no harmony orders yet');
            return self::FAILURE;
        }

        foreach ($orders as $order){

            $positionHistoryResponse = Exchange::positionHistory($order->coin->symbol(), $order->position_id);

            if (!$positionHistoryResponse->isSuccess()){
                $this->error('Position history response failed');
                return self::FAILURE;
            }

            if (is_null($positionHistoryResponse->position())){
                $this->info('Position item not found');
                return self::FAILURE;
            }

            $order->update([
               'status' => OrderStatusEnum::CLOSED
            ]);

            if ($harmonyStrategy->compound()){
                $harmonyStrategy->addToMargin($positionHistoryResponse->position()->getRealizeProfit());
            }
        }
    }
}
