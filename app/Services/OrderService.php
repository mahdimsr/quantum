<?php

namespace App\Services;

use App\Services\Exchange\Facade\Exchange;
use App\Services\Exchange\Repository\Order;
use Exception;

class OrderService
{
    public static function open(string $symbol, mixed $currentPrice, mixed $takeProfit, mixed $stopLoss, string $position = 'long', mixed $leverage = 10): Order
    {
        $side = $position == 'long' ? 'buy' : 'sell';

        Exchange::adjustPositionLeverage($symbol, 'futures', 'isolated', $leverage);

        $order = Exchange::placeOrder($symbol, 'futures', $side, 'limit', '', '');

        Exchange::setTakeProfit($symbol, 'futures', '', $takeProfit);

        Exchange::setStopLoss($symbol, 'futures', '', $stopLoss);

        return $order;
    }
}
