<?php

namespace App\Models;

use App\Services\Exchange\Repository\Order as OrderRepository;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Order extends Model
{
    protected $guarded = ['id'];

    public static function storeOrderRecord(OrderRepository $order, $futuresOrderPrices)
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
        ]);
    }

    public function stop_loss_price()
    {
        return Attribute::make(
            get: fn(string $value) => Str::of($value)->toFloat()
        );
    }

    public function take_profit_price()
    {
        return Attribute::make(
            get: fn(string $value) => Str::of($value)->toFloat()
        );
    }

    public function current_price()
    {
        return Attribute::make(
            get: fn(string $value) => Str::of($value)->toFloat()
        );
    }
}
