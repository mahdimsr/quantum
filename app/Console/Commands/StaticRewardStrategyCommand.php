<?php

namespace App\Console\Commands;

use App\Enums\TimeframeEnum;
use App\Models\Coin;
use App\Models\User;
use App\Notifications\SignalNotification;
use App\Services\Exchange\Facade\Exchange;
use App\Services\Order\Calculate;
use App\Services\OrderService;
use App\Services\Strategy\UTBotAlertStrategy;
use App\Traits\CommandSuccessOutput;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class StaticRewardStrategyCommand extends Command
{
    use CommandSuccessOutput;

    protected Coin $coin;

    protected $signature = 'strategy:static-reward {coin} {--T|timeframe=1H}';

    protected $description = 'set tp/sl by calculating base on order price';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->coin = Coin::findByName($this->argument('coin'));
        $symbol = $this->coin->USDTSymbol();

        $timeframe = $this->option('timeframe');
        $timeframe = TimeframeEnum::from($timeframe)->toCoinexFormat();

        try {

            $candlesResponse = Exchange::candles($symbol, $timeframe);

            $utbot = new UTBotAlertStrategy($candlesResponse->data(), 1);

            $lastExitingPosition = $utbot->lastPosition();
            $lastCandle = $candlesResponse->data()->lastCandle();

            $takeProfit = $lastCandle->getClose();
            $stopLoss = $lastCandle->getClose();

            if ($this->validateSignal($utbot) and $lastExitingPosition->getMeta()['order'] == 'buy') {

                $takeProfit = Calculate::target($lastCandle->getClose(), 1);
                $stopLoss = max(Calculate::target($lastCandle->getClose(), -5), $lastCandle->getLow());

                $this->setOrder($lastCandle->getClose(),$takeProfit, $stopLoss, 'long');
            }

            if ($this->validateSignal($utbot) and $lastExitingPosition->getMeta()['order'] == 'sell') {

                $takeProfit = Calculate::target($lastCandle->getClose(), -1);
                $stopLoss = max(Calculate::target($lastCandle->getClose(), 5), $lastCandle->getHigh());

                $this->setOrder($lastCandle->getClose(),$takeProfit, $stopLoss, 'short');
            }




        }catch (\Exception $exception) {

            logs()->channel('strategy')->error($exception);

            $this->error("static strategy failed...");
        }
    }

    private function validateSignal(UTBotAlertStrategy $utbot): bool
    {
        $lasTriggerdPositionIndex = collect($utbot->triggeredPositions())->keys()->last();
        $currentPositionIndex = $utbot->getCalculatedCandles()->keys()->last();

        return ($currentPositionIndex - $lasTriggerdPositionIndex) <= 2;
    }

    private function setOrder($price, $tp, $sl, $position): void
    {
        $availableAmount = OrderService::getAvailableAmount();

        if($availableAmount > 3) {

            $availableAmount = 3;
        }

        $leverage = $this->coin->leverage;

        $maxOrderAmount = Calculate::maxOrderAmount($price,$availableAmount,$leverage);

        OrderService::set($this->coin->USDTSymbol(), $price, $maxOrderAmount,$tp,$sl,$position, $leverage);
    }
}
