<?php

namespace App\Services;

use App\Enums\OrderStatusEnum;
use App\Enums\PositionTypeEnum;
use App\Models\Coin;
use App\Models\Order;
use Illuminate\Support\Str;

class OrderService
{
    public static function openOrder(Coin $coin, mixed $currentPrice, PositionTypeEnum $positionTypeEnum)
    {
        $pendingOrder = Order::query()->create([
           'symbol' => $coin->symbol('-'),
           'coin' => $coin->name,
           'side' => Str::of($positionTypeEnum->value)->upper()->toString(),
           'type' => Str::of($positionTypeEnum->value)->upper()->toString(),
           'status' => Str::of(OrderStatusEnum::PENDING->value)->upper()->toString(),
           'price' => $currentPrice
        ]);
   }
}
