<?php

namespace App\Console\Commands;

use App\Enums\OrderStatusEnum;
use App\Enums\PositionTypeEnum;
use App\Events\PendingOrderCreated;
use App\Models\Coin;
use App\Models\Order;
use App\Services\Exchange\Enums\SideEnum;
use App\Services\Exchange\Enums\TimeframeEnum;
use App\Services\Exchange\Enums\TypeEnum;
use App\Services\Exchange\Facade\Exchange;
use App\Services\Order\Calculate;
use App\Services\Strategy\Defaults\HarmonyStrategy;
use Illuminate\Console\Command;

class HarmonyStrategyCommand extends Command
{
    protected $signature = 'app:harmony-strategy';

    protected $description = 'Is`n it clear what this command do?';

    public function handle(): int
    {
        $harmonyStrategy = app(HarmonyStrategy::class);

        if (!$harmonyStrategy->active()) {
            $this->error('The harmony strategy is not active');
            return self::FAILURE;
        }

        $ordersWithHarmonyStrategiesCount = Order::query()
            ->where('strategy', $harmonyStrategy->name())
            ->whereDate('created_at', today())
            ->count();

        if ($ordersWithHarmonyStrategiesCount >= $harmonyStrategy->maxPositions()) {
            $this->error('Harmony strategy has reached the daily maximum positions');
            return self::FAILURE;
        }

        foreach ($harmonyStrategy->coins() as $coinName) {
            $coin = Coin::findByName($coinName);
            $timeframe = TimeframeEnum::from($harmonyStrategy->timeframe())->toCoineXFormat();
            $candleResponse  = Exchange::candles($coin->symbol(), $timeframe, 100);
            $positionType = $harmonyStrategy->signal($candleResponse->data());

            if (!is_null($positionType)) {
                $price = $candleResponse->data()->get(0)->getClose();
                $side = $positionType == PositionTypeEnum::SHORT ? SideEnum::SHORT : SideEnum::LONG;
                $tp = Calculate::target($price, $harmonyStrategy->takeProfitPercentage());
                $sl = Calculate::target($price, -$harmonyStrategy->takeProfitPercentage());

                $order = Order::query()->create([
                    'symbol' => $coin->symbol(),
                    'coin_name' => $coin->name,
                    'exchange' => 'coinex',
                    'leverage' => $harmonyStrategy->leverage(),
                    'side' => $side,
                    'type' => TypeEnum::MARKET,
                    'status' => OrderStatusEnum::ONLY_CREATED,
                    'price' => $price,
                    'sl' => $sl,
                    'tp' => $tp,
                    'strategy' => $harmonyStrategy->name(),
                    'balance' => $harmonyStrategy->margin(),
                ]);

                event(new PendingOrderCreated($order));
            }
        }
    }
}
