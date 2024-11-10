<?php

namespace App\Console\Commands;

use App\Enums\OrderStatusEnum;
use App\Enums\StrategyEnum;
use App\Events\OrderClosedEvent;
use App\Models\Coin;
use App\Models\Order;
use App\Services\Exchange\BingX\BingXService;
use App\Services\Exchange\Facade\Exchange;
use Illuminate\Console\Command;

class ClosePositionCommand extends Command
{
    protected $signature = 'app:close-position-command {--timeBase} {--percentageBase}';

    protected $description = 'Close Position';


    public function handle(): int
    {
        $pendingOrders = Order::strategy(StrategyEnum::Static_Profit)->status(OrderStatusEnum::PENDING)->get();

        foreach ($pendingOrders as $order) {

            $this->info('getting position of: ' . $order->coin_name);


            $currentPositionResponse = Exchange::currentPosition($order->coin->symbol('-'));

            if ($currentPositionResponse->isSuccess() and $currentPositionResponse->position()) {

                $position = $currentPositionResponse->position();

                $this->info('positions exists with position_id: ' . $position->getPositionId());

                $order->update([
                    'position_id' => $position->getPositionId(),
                ]);


                if ($this->option('timeBase') and now()->diffInMinutes($order->created_at) > 120) {

                    $this->comment('closing position');

                    $closePositionResponse = Exchange::closePositionByPositionId($position->getPositionId());

                    if ($closePositionResponse->isSuccess()) {

                        $this->info('position closed after 120 minutes');

                        event(new OrderClosedEvent($order));

                    } else {

                        $this->error('closing position failed');
                    }

                }

                if ($this->option('percentageBase') and $position->getPnlPercent() >= 10) {

                    $this->comment('closing position');

                    $closePositionResponse = Exchange::closePositionByPositionId($position->getPositionId());

                    if ($closePositionResponse->isSuccess()) {

                        $this->info('position closed after 10 percent profit');

                        event(new OrderClosedEvent($order));

                    } else {

                        $this->error('closing position failed');
                    }
                }

                $this->warn('no option set');

                return 1;
            }

        }

        $this->warn('no positions founded');

        return 0;
    }
}
