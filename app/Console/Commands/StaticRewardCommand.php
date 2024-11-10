<?php

namespace App\Console\Commands;

use App\Enums\OrderStatusEnum;
use App\Enums\StrategyEnum;
use App\Events\PendingOrderCreated;
use App\Models\Coin;
use App\Models\Order;
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
use Illuminate\Support\Str;

class StaticRewardCommand extends Command
{
    protected $signature = 'app:static-reward-strategy {--leverage=10} {timeframe=1h} {--coin=}';

    protected $description = 'Static Reward Strategy';


    public function handle(): int
    {
        $coin = Coin::findByName($this->option('coin'));
        $leverage = $this->argument('leverage');
        $timeframe = $this->argument('timeframe');

        $todayOrder = Order::strategy(StrategyEnum::Static_Profit)->whereDate('created_at', today())->exists();

        if ($todayOrder) {

            $this->info('Today order exists');

            return 0;
        }

        $balance = User::mahdi()->strategyBalance(StrategyEnum::Static_Profit);

        $candlesResponse = Exchange::candles($coin->symbol('-'), $timeframe, 100);

        if ($candlesResponse->isSuccess()) {

            $utbotStrategySmall = new UTBotAlertStrategy($candlesResponse->data(), 1, 2);
            $utbotStrategyBig = new UTBotAlertStrategy($candlesResponse->data(), 2, 3);


            if ( $utbotStrategyBig->isBullish() or $utbotStrategySmall->isBullish() ) {

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
                        'strategy' => StrategyEnum::Static_Profit,
                        'balance' => $balance,
                    ]);

                    event(new PendingOrderCreated($order));

                    return 1;
                }
            }


        }
    }
}
