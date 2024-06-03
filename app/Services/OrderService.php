<?php

namespace App\Services;

use App\Exceptions\OrderException;
use App\Models\User;
use App\Notifications\ExceptionNotification;
use App\Notifications\SignalNotification;
use App\Services\Exchange\Facade\Exchange;
use App\Services\Exchange\Repository\Order;
use Exception;
use Illuminate\Support\Facades\Notification;

class OrderService
{
    public static function getAvailableAmount(): int|float
    {
        return Exchange::futuresBalance()->data()->ccy('USDT')->getAvailable();
    }

    /**
     * @throws OrderException
     */
    public static function set(string $symbol, mixed $currentPrice, mixed $amount, mixed $takeProfit, mixed $stopLoss, string $position = 'long', mixed $leverage = 10): Order
    {
        $side = $position == 'long' ? 'buy' : 'sell';

        $user = User::findByEmail('mahdi.msr4@gmail.com');

        $leverageResponse = Exchange::adjustPositionLeverage($symbol, 'futures', 'isolated', $leverage);

        if (! $leverageResponse->isSuccess()){

            Notification::send($user, new ExceptionNotification($symbol, $leverageResponse->message()));

            throw OrderException::leverageFailed($leverageResponse->message());
        }

        sleep(1);

        $placeOrderResponse = Exchange::placeOrder($symbol, 'futures', $side, 'limit', $amount, $currentPrice);

        if (! $placeOrderResponse->isSuccess()) {

            Notification::send($user, new ExceptionNotification($symbol, $placeOrderResponse->message()));

            throw OrderException::placeOrderFailed($placeOrderResponse->message());
        }

        $order = $placeOrderResponse->order();

        Notification::send($user, new SignalNotification($symbol, $position, 'Static Hourly Reward'));

        $futuresOrderPrices = [
            'current_price' => $currentPrice,
            'stop_loss_price' => $stopLoss,
            'take_profit_price' => $takeProfit
        ];

        \App\Models\Order::storeOrderRecord($order, $futuresOrderPrices);

        return $order;
    }
}
