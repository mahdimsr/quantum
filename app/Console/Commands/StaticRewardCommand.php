<?php

namespace App\Console\Commands;

use App\Enums\StrategyEnum;
use App\Models\Coin;
use App\Models\User;
use App\Notifications\SignalNotification;
use App\Services\Exchange\Facade\Exchange;
use App\Services\Strategy\LNLTrendStrategy;
use App\Services\Strategy\UTBotAlertStrategy;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class StaticRewardCommand extends Command
{
    protected $signature = 'app:static-reward-strategy {coin} {profit-percent=1} {leverage=5} {timeframe=1h}';

    protected $description = 'Static Reward Strategy';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $coin = Coin::findByName($this->argument('coin'));
        $profitPercent = $this->argument('profit-percent');
        $leverage = $this->argument('leverage');
        $timeframe = $this->argument('timeframe');

        $this->info("Getting Candles of $coin->name");

        $candlesResponse = Exchange::candles($coin->symbol('-'),$timeframe,100);

        if ($candlesResponse->data()->isEmpty()) {

            $this->error("$coin->name candles is empty");

            $coin->delete();

            return 0;
        }

        $this->info("Setting ut-bot and lnl-trend...");

        $utBotStrategy = new UTBotAlertStrategy($candlesResponse->data(), 1, 5);
        $lnlTrendStrategy = new LNLTrendStrategy($candlesResponse->data());

        if ($utBotStrategy->isBuy(3) and $lnlTrendStrategy->isBullish()) {

            Notification::send(User::mahdi(), new SignalNotification($coin->name,'buy', 'Static Reward'));

            $this->info('Buy Signal Sent...');

            return 1;
        }

        if ($utBotStrategy->isSell(3) and $lnlTrendStrategy->isBearish()) {

            Notification::send(User::mahdi(), new SignalNotification($coin->name,'sell', 'Static Reward'));

            $this->info('Sell Signal Sent...');

            return 1;
        }

        $this->comment('No Signal detected ...');

        return 1;
    }
}
