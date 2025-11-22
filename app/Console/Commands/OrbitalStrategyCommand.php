<?php

namespace App\Console\Commands;

use App\Enums\OrderStatusEnum;
use App\Enums\PositionTypeEnum;
use App\Enums\StrategyEnum;
use App\Events\PendingOrderCreated;
use App\Models\Coin;
use App\Models\Order;
use App\Services\Exchange\Enums\SideEnum;
use App\Services\Exchange\Enums\TimeframeEnum;
use App\Services\Exchange\Enums\TypeEnum;
use App\Services\Exchange\Facade\Exchange;
use App\Services\Strategy\Defaults\OrbitalStrategy;
use Illuminate\Console\Command;

class OrbitalStrategyCommand extends Command
{
    protected $signature = 'app:orbital-strategy';

    protected $description = 'Isn`t it clear what this command do?';

    public function handle(): int
    {
        $orbitalStrategy = app(OrbitalStrategy::class);

        if (!$orbitalStrategy->active()) {
             $this->error('The orbital strategy is not active');
             return 0;
        }

        foreach ($orbitalStrategy->coins() as $coinName) {

            $coin = Coin::findByName($coinName);
            $timeframe = Exchange::convertedTimeframe(TimeframeEnum::from($orbitalStrategy->timeframe()));
            $candleResponse  = Exchange::candles($coin->symbol(), $timeframe, 100);
            $positionType = $orbitalStrategy->signal($candleResponse->data());

            if (!is_null($positionType)) {

                $side = $positionType == PositionTypeEnum::SHORT ? SideEnum::SHORT : SideEnum::LONG;
                $sl = $candleResponse->data()->get(2)->getClose();
                $price = $candleResponse->data()->get(0)->getClose();


                $order = Order::query()->create([
                    'symbol' => $coin->symbol(),
                    'coin_name' => $coin->name,
                    'exchange' => 'coinex',
                    'leverage' => $orbitalStrategy->leverage(),
                    'side' => $side,
                    'type' => TypeEnum::MARKET,
                    'status' => OrderStatusEnum::ONLY_CREATED,
                    'price' => $price,
                    'sl' => $sl,
                    'strategy' => $orbitalStrategy->name(),
                    'balance' => $orbitalStrategy->margin(),
                ]);

                event(new PendingOrderCreated($order));
            }
        }

        return 0;
    }
}
