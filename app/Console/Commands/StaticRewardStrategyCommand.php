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

            if ($lastExitingPosition->getMeta()['order'] == 'buy'){

                $takeProfit = Calculate::target($lastCandle->getClose(), 1);
                $stopLoss = $lastCandle->getLow();

                $this->setOrder($lastCandle->getClose(),$takeProfit, $stopLoss, 'long');
            }

            if ($lastExitingPosition->getMeta()['order'] == 'sell'){

                $takeProfit = Calculate::target($lastCandle->getClose(), -1);
                $stopLoss = $lastCandle->getHigh();

                $this->setOrder($lastCandle->getClose(),$takeProfit, $stopLoss, 'short');
            }




        }catch (\Exception $exception) {

            logs()->channel('strategy')->error($exception);

            $this->error("static strategy failed...");
        }
    }


    private function setOrder($price, $tp, $sl, $position): void
    {
        $availableAmount = OrderService::getAvailableAmount();

        $leverage = $this->coin->leverage;

        $maxOrderAmount = Calculate::maxOrderAmount($price,$availableAmount,$leverage);

        $orderAmount = $maxOrderAmount;

        OrderService::set($this->coin->USDTSymbol(), $price, $orderAmount,$tp,$sl,$position, $leverage);

    }
}
