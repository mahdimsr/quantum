<?php

namespace App\Console\Commands;

use App\Enums\OrderStatusEnum;
use App\Events\OrderClosedEvent;
use App\Models\Order;
use App\Services\Exchange\Enums\SideEnum;
use App\Services\Exchange\Enums\TimeframeEnum;
use App\Services\Exchange\Facade\Exchange;
use App\Services\Indicator\Facade\Indicator;
use App\Services\Indicator\Strategy\UTBotAlertStrategy;
use App\Services\Strategy\Defaults\OrbitalStrategy;
use Illuminate\Console\Command;

class CloseOrbitalOrdersCommand extends Command
{
    protected $signature = 'app:close-orbital-orders';

    protected $description = 'This command close order which opened by orbital strategy base on strategy setting';

    public function handle(): int
    {
        $orbitalStrategy = app(OrbitalStrategy::class);

        $orders = Order::strategy('orbital')->where('status', OrderStatusEnum::OPEN->value)->get();

        if ($orders->isEmpty()) {
            $this->error('No orbital orders found');
            return self::FAILURE;
        }

        foreach ($orders as $order) {

            $timeframe = Exchange::convertedTimeframe(TimeframeEnum::EVERY_FIFTEEN_MINUTES);
            $candlesResponse = Exchange::candles($order->coin->symbol(), $timeframe, 100);
            $candleCollection = $candlesResponse->data();

            if ($orbitalStrategy->autoClose()) {
                $ema3 = Indicator::EMA($candleCollection, 3);
                $lastCandle = $candleCollection->lastCandle();

                if ($order->side == SideEnum::LONG and $lastCandle->getClose() < $lastCandle->getMeta('ema-3')) {
                    $closeOrderResponse = Exchange::closePositionByPositionId($order->position_id, $order->symbol);

                    if ($closeOrderResponse->isSuccess()) {

                        event(new OrderClosedEvent($order));
                        return self::SUCCESS;
                    }
                }

                if ($order->side == SideEnum::SHORT and $lastCandle->getClose() > $lastCandle->getMeta('ema-3')) {
                    $closeOrderResponse = Exchange::closePositionByPositionId($order->position_id, $order->symbol);

                    if ($closeOrderResponse->isSuccess()) {

                        event(new OrderClosedEvent($order));
                        return self::SUCCESS;
                    }
                }

            }

            if ($orbitalStrategy->stopLossType() == 'large-utbot') {
                $largeUtbot = new UTBotAlertStrategy($candlesResponse->data(), 2, 3);
                $sl = $largeUtbot->collection()->get(0)->getMeta('trailing-stop');
                $stopLossResponse = Exchange::setStopLoss($order->coin->symbol(), $sl, 'something');
                if ($stopLossResponse->isSuccess()) {
                    $order->update([
                        'sl' => $sl
                    ]);
                }
            }

            if ($orbitalStrategy->stopLossType() == 'last-candle') {
                $lastCandle = $candlesResponse->data()->get(1);
                $sl = ($lastCandle->getClose() + $lastCandle->getOpen()) / 2;
                if ($order->side == SideEnum::SHORT){
                    $sl = max(
                        $lastCandle->getClose(),
                        $lastCandle->getOpen()
                    );
                }
                if ($order->side == SideEnum::LONG){
                    $sl = min(
                        $lastCandle->getClose(),
                        $lastCandle->getOpen()
                    );
                }
                $stopLossResponse = Exchange::setStopLoss($order->coin->symbol(), $sl, 'something');
                if ($stopLossResponse->isSuccess()) {
                    $order->update([
                        'sl' => $sl
                    ]);
                }
            }

        }

        return self::SUCCESS;
    }
}
