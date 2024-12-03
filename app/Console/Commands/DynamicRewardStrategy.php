<?php

namespace App\Console\Commands;

use App\Enums\OrderStatusEnum;
use App\Enums\StrategyEnum;
use App\Events\PendingOrderCreated;
use App\Models\Coin;
use App\Models\Order;
use App\Models\User;
use App\Services\Exchange\Enums\SideEnum;
use App\Services\Exchange\Enums\TypeEnum;
use App\Services\Exchange\Facade\Exchange;
use App\Services\Indicator\Strategy\LNLTrendStrategy;
use App\Services\Indicator\Strategy\UTBotAlertStrategy;
use Illuminate\Console\Command;

class DynamicRewardStrategy extends Command
{
    protected $signature = 'app:dynamic-reward-strategy {--coin=} {--timeframe=1h} {--leverage=10}';

    protected $description = 'Dynamic Reward Strategy';


    public function handle(): int
    {
        $coin = Coin::findByName($this->option('coin'));
        $timeframe = $this->option('timeframe');
        $leverage = $this->option('leverage');

        if (Order::status(OrderStatusEnum::PENDING)->where('coin_name', $coin->name)->exists()) {

            $this->warn('pending order exists...');

            return 0;
        }

        $balance = User::mahdi()->strategyBalance(StrategyEnum::DYNAMIC_REWARD);
        $availableBalance = Exchange::futuresBalance()->availableMargin();

        if ($availableBalance < $balance) {

            $balance = $availableBalance;
            $leverage = $leverage * 2;
        }


        $candlesResponse = Exchange::candles($coin->symbol('-'), $timeframe, 100);

        if ($candlesResponse->isSuccess()) {

            $utbotStrategySmall = new UTBotAlertStrategy($candlesResponse->data(), 1, 2);
            $utbotStrategyBig = new UTBotAlertStrategy($candlesResponse->data(), 2, 3);
            $lnlTrendStrategy = new LNLTrendStrategy($candlesResponse->data());

            if ($lnlTrendStrategy->isBullish() and ($utbotStrategyBig->isBullish() or $utbotStrategySmall->isBullish())) {

                if ($utbotStrategySmall->buySignal() or $utbotStrategySmall->buySignal(1) or $utbotStrategyBig->buySignal() or $utbotStrategyBig->buySignal(1)) {

                    $this->info('Buy Order');

                    $price = $utbotStrategySmall->collection()->get(0)->getClose();

                    // current trailing-stop or previous open

                    $sl = min(
                        $utbotStrategyBig->collection()->get(0)->getMeta('trailing-stop'),
                        $utbotStrategyBig->collection()->get(1)->getOpen()
                    );

                    $order = Order::query()->create([
                        'symbol' => $coin->symbol('-'),
                        'coin_name' => $coin->name,
                        'leverage' => $leverage,
                        'side' => SideEnum::BUY,
                        'type' => TypeEnum::MARKET,
                        'status' => OrderStatusEnum::ONLY_CREATED,
                        'price' => $price,
                        'sl' => $sl,
                        'strategy' => StrategyEnum::DYNAMIC_REWARD,
                        'balance' => $balance,
                    ]);

                    event(new PendingOrderCreated($order));

                    return 1;
                }

            }

            if ($lnlTrendStrategy->isBearish() and ($utbotStrategyBig->isBearish() or $utbotStrategySmall->isBearish())) {

                if ($utbotStrategySmall->sellSignal() or $utbotStrategySmall->sellSignal(1) or $utbotStrategyBig->sellSignal() or $utbotStrategyBig->sellSignal(1)) {

                    $this->info('Sell Order');

                    $price = $utbotStrategySmall->collection()->get(0)->getClose();

                    // current trailing-stop or previous open

                    $sl = max(
                        $utbotStrategyBig->collection()->get(0)->getMeta('trailing-stop'),
                        $utbotStrategyBig->collection()->get(1)->getOpen()
                    );

                    $order = Order::query()->create([
                        'symbol' => $coin->symbol('-'),
                        'coin_name' => $coin->name,
                        'leverage' => $leverage,
                        'side' => SideEnum::SHORT,
                        'type' => TypeEnum::MARKET,
                        'status' => OrderStatusEnum::ONLY_CREATED,
                        'price' => $price,
                        'sl' => $sl,
                        'strategy' => StrategyEnum::DYNAMIC_REWARD,
                        'balance' => $balance,
                    ]);

                    event(new PendingOrderCreated($order));

                    return 1;
                }
            }

            $this->warn('No entry found');

            return 1;
        }

        $this->error("$coin->name getting candles was not successful");

        return 0;
    }
}
