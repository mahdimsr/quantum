<?php

namespace App\Console\Commands;

use App\Enums\OrderStatusEnum;
use App\Enums\StrategyEnum;
use App\Events\OrderClosedEvent;
use App\Models\Order;
use App\Services\Exchange\Facade\Exchange;
use App\Services\Indicator\Strategy\UTBotAlertStrategy;
use Illuminate\Console\Command;

class ClosePositionCommand extends Command
{
    protected $signature = 'app:close-position {--timeframe=1hour}';

    protected $description = 'Command description';

    public function handle(): void
    {
        $timeframe = $this->option('timeframe');

        $orders = Order::strategy(StrategyEnum::DYNAMIC_REWARD)
            ->status(OrderStatusEnum::OPEN)
            ->get();

        foreach ($orders as $order) {

            if (! $order->position_id){

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

            $candlesResponse = Exchange::candles($order->coin->symbol(), $timeframe, 100);

            $utbotStrategySmall = new UTBotAlertStrategy($candlesResponse->data(), 1, 2);

            if ($utbotStrategySmall->sellSignal(1)) {

                $closeOrderResponse = Exchange::closePositionByPositionId($order->position_id, $order->symbol);

                if ($closeOrderResponse->isSuccess()) {

                    event(new OrderClosedEvent($order));
                }

            }
        }
    }
}
