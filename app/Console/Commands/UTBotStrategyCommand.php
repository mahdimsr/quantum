<?php

namespace App\Console\Commands;

use App\Enums\StrategyEnum;
use App\Enums\TimeframeEnum;
use App\Models\Coin;
use App\Models\User;
use App\Notifications\SignalNotification;
use App\Services\Exchange\Facade\Exchange;
use App\Services\Strategy\UTBotAlertStrategy;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class UTBotStrategyCommand extends Command
{
    protected $signature = 'strategy:utbot {coin} {timeframe=4H}';

    protected $description = 'Check if utbot has buy/sell signal';

    private Coin $coin;


    public function handle()
    {
        $this->coin = Coin::findByName($this->argument('coin'));
        $timeframe = TimeframeEnum::from($this->argument('timeframe'));

        $candlesResponse = Exchange::candles($this->coin->USDTSymbol(),$timeframe->toCoinexFormat());

        $utbotStrategy = new UTBotAlertStrategy($candlesResponse->data(), 1, 20);

        if ($utbotStrategy->buy() and $utbotStrategy->hasRecentlySignal()) {

            Notification::send(User::mahdi(), new SignalNotification($this->coin->USDTSymbol(),'buy',StrategyEnum::UT_BOT_ALERT->value));
        }

        if ($utbotStrategy->sell() and $utbotStrategy->hasRecentlySignal()) {

            Notification::send(User::mahdi(), new SignalNotification($this->coin->USDTSymbol(),'sell',StrategyEnum::UT_BOT_ALERT->value));
        }
    }
}
