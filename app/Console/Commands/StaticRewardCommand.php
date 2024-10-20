<?php

namespace App\Console\Commands;

use App\Enums\StrategyEnum;
use App\Models\Coin;
use App\Models\User;
use App\Notifications\ExceptionNotification;
use App\Services\Exchange\Enums\SideEnum;
use App\Services\Exchange\Enums\TypeEnum;
use App\Services\Exchange\Facade\Exchange;
use App\Services\Order\Calculate;
use App\Services\OrderService;
use App\Services\Strategy\LNLTrendStrategy;
use App\Services\Strategy\UTBotAlertStrategy;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class StaticRewardCommand extends Command
{
    protected $signature = 'app:static-reward-strategy {profit-percent=1} {leverage=5} {timeframe=1h}';

    protected $description = 'Static Reward Strategy';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $leverage = $this->argument('leverage');
        $timeframe = $this->argument('timeframe');

        $exchangeBalance = Exchange::futuresBalance()->balance();
        $strategyBalance = User::mahdi()->strategies()->where('name', StrategyEnum::Static_Profit->value)->first();

        if ($exchangeBalance < $strategyBalance) {

            $this->alert("this strategy for you has balance: $strategyBalance, but your exchange balance is: $exchangeBalance");

            Notification::send(User::mahdi(), new ExceptionNotification('Balance not enough to reach static-reward strategy'));

            return 0;
        }

        $staticRewardCoins = Coin::withStrategy(StrategyEnum::Static_Profit)->get();

        foreach ($staticRewardCoins as $coin) {

            $this->info("Getting Candles of $coin->name");

            $candlesResponse = Exchange::candles($coin->symbol('-'), $timeframe, 100);


            if ($candlesResponse->data()->isEmpty()) {

                $this->error("$coin->name candles is empty");

                $coin->delete();

                return 0;
            }

            $this->info("Setting ut-bot and lnl-trend...");

            $utBotStrategy = new UTBotAlertStrategy($candlesResponse->data(), 1, 5);
            $lnlTrendStrategy = new LNLTrendStrategy($candlesResponse->data());

            if ($utBotStrategy->isBuy(1) and $lnlTrendStrategy->isBullish()) {

                $sl = Calculate::target($utBotStrategy->currentPrice(), -0.5);
                $tp = Calculate::target($utBotStrategy->currentPrice(), 0.5);

                OrderService::openOrder(
                    $coin,
                    $utBotStrategy->currentPrice(),
                    TypeEnum::LIMIT,
                    SideEnum::LONG,
                    $sl,
                    $tp,
                    $leverage
                );

                $this->info('Buy Signal Sent ...');

                return 1;
            }

            if ($utBotStrategy->isSell(1) and $lnlTrendStrategy->isBearish()) {

                $sl = Calculate::target($utBotStrategy->currentPrice(), 0.5);
                $tp = Calculate::target($utBotStrategy->currentPrice(), - 0.5);

                OrderService::openOrder(
                    $coin,
                    $utBotStrategy->currentPrice(),
                    TypeEnum::LIMIT,
                    SideEnum::SHORT,
                    $sl,
                    $tp,
                    $leverage
                );

                $this->info('Sell Signal Sent ...');

                return 1;
            }
        }


        $this->comment('No Signal detected ...');

        return 1;
    }
}
