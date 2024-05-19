<?php

namespace App\Services;

use App\Services\Exchange\Facade\Exchange;
use App\Services\Exchange\Repository\Order;
use Exception;

class OrderService
{
    public static function long(string $symbol,mixed $currentPrice, mixed $percent = 1): Order
    {

        Exchange::adjustPositionLeverage($symbol,'futures','isolated', 10);

        $order = Exchange::placeOrder($symbol,'futures','buy', 'limit', '','');

        Exchange::setTakeProfit($symbol,'futures','','');

        Exchange::setStopLoss($symbol,'futures','','');

        return $order;
    }
}
