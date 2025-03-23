<?php

namespace App\Console\Commands;

use App\Enums\OrderStatusEnum;
use App\Enums\StrategyEnum;
use App\Models\Order;
use App\Services\Exchange\Facade\Exchange;
use App\Services\Indicator\Strategy\UTBotAlertStrategy;
use Illuminate\Console\Command;

class UpdateDynamicStopLossCommand extends Command
{
    protected $signature = 'app:update-dynamic-stop-loss-command {--timeframe=1hour}';


    protected $description = 'This Command Find open positions in local DB and updated their SL base on indicator';

    public function handle()
    {
        $timeframe = $this->option('timeframe');

        $orders = Order::strategy(StrategyEnum::DYNAMIC_REWARD)
            ->status(OrderStatusEnum::OPEN)
            ->get();

        foreach ($orders as $order) {

            $currentPositionResponse = Exchange::currentPosition($order->coin->symbol());

            if ($currentPositionResponse->isSuccess()) {

                $order->update([
                    'position_id' => $currentPositionResponse->position()->getPositionId()
                ]);

            } else {

                $order->update([
                    'status' => OrderStatusEnum::UNKNOWN,
                ]);
            }

            $candlesResponse = Exchange::candles($order->coin->symbol(), $timeframe, 100);

            $utbotStrategySmall = new UTBotAlertStrategy($candlesResponse->data(), 2, 3);
            $sl = $utbotStrategySmall->collection()->get(0)->getMeta('trailing-stop');

            $stopLossResponse = Exchange::setStopLoss($order->coin->symbol(), $sl,'something');

            if ($stopLossResponse->isSuccess()) {

                $order->update([
                   'sl' => $sl
                ]);

                $this->info("{$order->coin->name} SL update");
            }
        }
    }
}
