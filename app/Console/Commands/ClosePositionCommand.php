<?php

namespace App\Console\Commands;

use App\Enums\OrderStatusEnum;
use App\Events\OrderClosedEvent;
use App\Models\Coin;
use App\Models\Order;
use App\Services\Exchange\BingX\BingXService;
use Illuminate\Console\Command;

class ClosePositionCommand extends Command
{
    protected $signature = 'app:close-position-command';

    protected $description = 'Close Position';

    private BingXService $bingXService;


    public function __construct()
    {
        parent::__construct();

        $this->bingXService = app(BingXService::class);
    }

    public function handle(): int
    {
        $pendingOrders = Order::status(OrderStatusEnum::PENDING)->get();

        foreach ($pendingOrders as $order) {

            $this->info('getting position of: ' . $order->coin_name);


            $currentPositionResponse = $this->bingXService->currentPosition($order->coin->symbol('-'));

            if ($currentPositionResponse->isSuccess() and $currentPositionResponse->position()) {

                $position = $currentPositionResponse->position();

                $this->info('positions exists with position_id: ' . $position->getPositionId());

                $order->update([
                    'position_id' => $position->getPositionId(),
                ]);


                if (now()->diffInMinutes($order->created_at) > 45 or $position->getPnlPercent() >= 1.5) {

                    $this->comment('closing position');

                    $closePositionResponse = $this->bingXService->closePositionByPositionId($position->getPositionId());

                    if ($closePositionResponse->isSuccess()) {

                        $this->info('position closed');

                        event(new OrderClosedEvent($order));

                    } else {

                        $this->error('closing position failed');
                    }

                }

                return 1;
            }

        }

        $this->warn('no positions founded');

        return 0;
    }
}
