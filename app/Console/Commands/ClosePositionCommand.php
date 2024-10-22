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
    protected $signature = 'app:close-position-command {--timeBase} {--percentageBase}';

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


                if ($this->option('timeBase') and now()->diffInMinutes($order->created_at) > 45) {

                    $this->comment('closing position');

                    $closePositionResponse = $this->bingXService->closePositionByPositionId($position->getPositionId());

                    if ($closePositionResponse->isSuccess()) {

                        $this->info('position closed after 45 minutes');

                        event(new OrderClosedEvent($order));

                    } else {

                        $this->error('closing position failed');
                    }

                }

                if ($this->option('percentageBase') and $position->getPnlPercent() >= 1.5) {

                    $this->comment('closing position');

                    $closePositionResponse = $this->bingXService->closePositionByPositionId($position->getPositionId());

                    if ($closePositionResponse->isSuccess()) {

                        $this->info('position closed after 1.5 percent profit');

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
