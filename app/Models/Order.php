<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public static function storeOrderRecord($order, $futuresOrderPrices)
    {
      return self::query()
          ->create([
            'order_id' => $order->getOrderId(),
            'market' => $order->getMarket(),
            'market_type' => $order->getMarketType(),
            'side' => $order->getSide(),
            'type' => $order->getType(),
            'amount' => $order->getAmount(),
            'current_price' => $futuresOrderPrices['current_price'],
            'price' => $order->getPrice(),
            'stop_loss_price' => $futuresOrderPrices['stop_loss_price'],
            'take_profit_price' => $futuresOrderPrices['take_profit_price'],
            'has_stop_loss' => false,
            'has_take_profit' => false,
            'unfilled_amount' => $order->getUnfilledAmount(),
            'filled_amount' => $order->getFilledAmount(),
            'filled_value' => $order->getFilledValue(),
            'client_id' => $order->getClientId(),
            'fee' => $order->getFee(),
            'fee_ccy' => $order->getFeeCcy(),
            'maker_fee_rate' => $order->getMakerFeeRate(),
            'taker_fee_rate' => $order->getTakerFeeRate(),
            'last_filled_amount' => null,
            'last_filled_price' => null,
            'realized_pnl' => null,
        ]);
    }
}
