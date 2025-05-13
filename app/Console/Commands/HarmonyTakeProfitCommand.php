<?php

namespace App\Console\Commands;

use App\Enums\OrderStatusEnum;
use App\Models\Order;
use App\Services\Exchange\Facade\Exchange;
use App\Services\Strategy\Defaults\HarmonyStrategy;
use Illuminate\Console\Command;

class HarmonyTakeProfitCommand extends Command
{
    protected $signature = 'app:harmony-tp';

    protected $description = 'This command update tp order with harmony strategies';

    public function handle(): int
    {
        $harmonyStrategy = app(HarmonyStrategy::class);

        $orders = Order::query()
            ->where('strategy', $harmonyStrategy->name())
            ->where('status', OrderStatusEnum::OPEN)
            ->get();

        if ($orders->isEmpty()) {
            $this->info('There is no orders yet');
            return self::SUCCESS;
        }

        foreach ($orders as $order) {

            $setTPResponse = Exchange::setTakeProfit($order->coin->symbol(), $order->tp, 'something');
            if ($setTPResponse->isSuccess()) {
                $order->update([
                    'status' => OrderStatusEnum::HAS_TP,
                ]);
            }
        }

        return self::SUCCESS;
    }
}
