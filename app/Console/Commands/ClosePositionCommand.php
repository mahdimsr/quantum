<?php

namespace App\Console\Commands;

use App\Events\OrderClosedEvent;
use App\Models\Coin;
use App\Services\Exchange\BingX\BingXService;
use Illuminate\Console\Command;

class ClosePositionCommand extends Command
{
    protected $signature = 'app:close-position-command {coin} {profit-percent=1}';

    protected $description = 'Close Position';

    private BingXService $bingXService;
    private ?Coin $coin;
    private ?int $tpPercent;


    public function __construct()
    {
        parent::__construct();

        $this->bingXService = app(BingXService::class);
    }

    public function handle()
    {
        $this->coin = Coin::findByName($this->argument('coin'));
        $this->tpPercent = $this->argument('profit-percent');

        $this->info('getting position of: ' . $this->coin->name);

        $currentPositionResponse = $this->bingXService->currentPosition($this->coin->symbol('-'));

        if ($currentPositionResponse->isSuccess() and $currentPositionResponse->position()) {

            $position = $currentPositionResponse->position();

            $this->info('positions exists with position_id: ' . $position->getPositionId());

            if ($position->getPnlPercent() >= $this->tpPercent) {

                $this->comment('closing position');

                $closePositionResponse = $this->bingXService->closePositionByPositionId($position->getPositionId());

                if ($closePositionResponse->isSuccess()) {

                    $this->info('position closed');

                    event(new OrderClosedEvent($closePositionResponse->order_id()));

                } else {

                    $this->error('closing position failed');
                }

            }

            return 1;
        }

        $this->warn('no positions founded');

        return 0;
    }
}
