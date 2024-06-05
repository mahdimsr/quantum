<?php

namespace App\Console\Commands;

use App\Enums\PriceTypeEnum;
use App\Models\Order;
use App\Services\Exchange\Facade\Exchange;
use Illuminate\Console\Command;

class CheckOrderCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check orders with has_stop_loss and has_take_profit';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $orders = Order::query()
            ->where('has_stop_loss', false)
            ->where('has_take_profit', false)
            ->get();

        foreach ($orders as $order) {

            $slResponse = Exchange::setStopLoss($order->market, PriceTypeEnum::MARK, $order->stop_loss_price);

            if($slResponse->isSuccess()) {
                $order->update(['has_stop_loss' => true]);
            } else {


                if ($order->side == 'sell') {

                    $stopLossRange = $order->stop_loss_price - $order->current_price;

                    $newStopLoss = $order->stop_loss_price + $stopLossRange;

                    $order->update(['stop_loss_price' => $newStopLoss]);
                }

                if ($order->side == 'buy') {

                    $stopLossRange = $order->current_price - $order->stop_loss_price;

                    $newStopLoss = $order->stop_loss_price - $stopLossRange;

                    $order->update(['stop_loss_price' => $newStopLoss]);
                }



            }
            sleep(1);

           $tpResponse = Exchange::setTakeProfit($order->market, PriceTypeEnum::MARK, $order->take_profit_price);
            if($tpResponse->isSuccess()) {
                $order->update(['has_take_profit' => true]);
            }
        }
    }
}
