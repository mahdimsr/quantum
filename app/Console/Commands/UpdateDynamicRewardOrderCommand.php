<?php

namespace App\Console\Commands;

use App\Enums\StrategyEnum;
use App\Events\OrderClosedEvent;
use App\Models\Order;
use App\Services\Exchange\Facade\Exchange;
use App\Services\Strategy\UTBotAlertStrategy;
use Illuminate\Console\Command;

class UpdateDynamicRewardOrderCommand extends Command
{
    protected $signature = 'app:update-dynamic-reward-order {--timeframe=1h}';


    protected $description = 'close order or update sl';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $timeframe = $this->option('timeframe');

        $orders = Order::strategy(StrategyEnum::DYNAMIC_REWARD)->get();

        foreach ($orders as $order) {

            $candlesResponse = Exchange::candles($order->coin->symbol('-'), $timeframe, 100);

            $utbotStrategySmall = new UTBotAlertStrategy($candlesResponse->data(), 1, 2);
            $utbotStrategyBig = new UTBotAlertStrategy($candlesResponse->data(), 2, 3);

            if (! $order->position_id) {

                $positionResponse = Exchange::currentPosition($order->coin->symbol('-'));

                if ($positionResponse->isSuccess()) {

                    $order->update([
                        'position_id' => $positionResponse->position()->getPositionId(),
                    ]);
                }
            }

            // TODO: update sl

            if ($order->side->isLong()) {

                if ($utbotStrategySmall->isSell(0)){

                    // close
                    if ($order->position_id) {

                        $closePositionResponse = Exchange::closePositionByPositionId($order->position_id);

                        if ($closePositionResponse->isSuccess()) {

                            event(new OrderClosedEvent($order));
                        }
                    }
                }
            }

            if ($order->side->isShort()) {

                if ($utbotStrategyBig->isBuy(0)){

                    // close
                    if ($order->position_id) {

                        $closePositionResponse = Exchange::closePositionByPositionId($order->position_id);

                        if ($closePositionResponse->isSuccess()) {

                            event(new OrderClosedEvent($order));
                        }
                    }
                }
            }
        }
    }
}
