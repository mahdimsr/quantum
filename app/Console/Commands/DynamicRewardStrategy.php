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
use Illuminate\Support\Str;

class DynamicRewardStrategy extends Command
{
    protected $signature = 'app:dynamic-reward-strategy {--coin=} {--timeframe=1hour} {--leverage=5} {--position=}';

    protected $description = 'Dynamic Reward Strategy';


    public function handle(): int
    {
        $coin = Coin::findByName($this->option('coin'));
        $timeframe = $this->option('timeframe');
        $leverage = $this->option('leverage');
        $position = $this->option('position');

        if (Order::status(OrderStatusEnum::OPEN)->where('coin_name', $coin->name)->exists()) {

            $this->warn('pending order exists...');

            return 0;
        }

        $balance = User::mahdi()->strategyBalance(StrategyEnum::DYNAMIC_REWARD);

        $availableBalance = Exchange::futuresBalance()->availableMargin();

        if ($availableBalance < $balance) {

            $balance = $availableBalance;
        }


        $candlesResponse = Exchange::candles($coin->symbol(), $timeframe, 100);

        if ($candlesResponse->isSuccess()) {

            $utbotStrategySmall = new UTBotAlertStrategy($candlesResponse->data(), 1, 2);
            $lnlTrendStrategy = new LNLTrendStrategy($candlesResponse->data());

            if ($lnlTrendStrategy->isBullish() and $utbotStrategySmall->isBullish() or Str::of($position)->contains('long')) {

                if ($utbotStrategySmall->buySignal(1) or Str::of($position)->contains('long')) {

                    $this->info('Buy Order');

                    $price = $utbotStrategySmall->collection()->get(0)->getClose();

                    // current trailing-stop or previous open

                    $sl = min(
                        $utbotStrategySmall->collection()->get(0)->getMeta('trailing-stop'),
                        $utbotStrategySmall->collection()->get(1)->getOpen()
                    );

                    $order = Order::query()->create([
                        'symbol' => $coin->symbol(),
                        'coin_name' => $coin->name,
                        'exchange' => 'coinex',
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

            if ($lnlTrendStrategy->isBearish() and $utbotStrategySmall->isBearish() or Str::of($position)->contains('short')) {

                if ($utbotStrategySmall->sellSignal(1) or Str::of($position)->contains('short')) {

                    $this->info('Sell Order');

                    $price = $utbotStrategySmall->collection()->get(0)->getClose();

                    // current trailing-stop or previous open

                    $sl = max(
                        $utbotStrategySmall->collection()->get(0)->getMeta('trailing-stop'),
                        $utbotStrategySmall->collection()->get(1)->getOpen()
                    );

                    $order = Order::query()->create([
                        'symbol' => $coin->symbol(),
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
