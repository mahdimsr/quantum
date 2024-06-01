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
        $orders = Order::where('has_stop_loss', false)
            ->where('has_take_profit', false)
            ->get();

        foreach ($orders as $order) {

            $slResponse = Exchange::setStopLoss($order->market, PriceTypeEnum::MARK, $order->stop_loss_price);

            if($slResponse['message'] == 'OK') {
                $order->update(['has_stop_loss' => true]);
            }
            sleep(1);

           $tpResponse = Exchange::setTakeProfit($order->market, PriceTypeEnum::MARK, $order->take_profit_price);
            if($tpResponse['message'] == 'OK') {
                $order->update(['has_take_profit' => true]);
            }
        }
    }
}
