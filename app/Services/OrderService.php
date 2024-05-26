<?php

namespace App\Services;

use App\Services\Exchange\Facade\Exchange;
use App\Services\Exchange\Repository\Order;
use Exception;

class OrderService
{
    public static function getAvailableAmount(): int|float
    {
        return Exchange::futuresBalance()->data()->ccy('USDT')->getAvailable();
    }

    public static function set(string $symbol, mixed $currentPrice, mixed $amount, mixed $takeProfit, mixed $stopLoss, string $position = 'long', mixed $leverage = 10): Order
    {
        $side = $position == 'long' ? 'buy' : 'sell';

        $leverageResponse = Exchange::adjustPositionLeverage($symbol, 'futures', 'isolated', $leverage);

        sleep(1);

        $order = Exchange::placeOrder($symbol, 'futures', $side, 'market', $amount, $currentPrice);

        sleep(1);

        Exchange::setStopLoss($symbol, 'futures', 'mark_price', $stopLoss);

        sleep(1);

        Exchange::setTakeProfit($symbol, 'futures', 'mark_price', $takeProfit);

        return $order;
    }
}
