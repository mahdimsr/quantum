<?php

namespace App\Services;

use App\Enums\OrderStatusEnum;
use App\Events\PendingOrderCreated;
use App\Models\Coin;
use App\Models\Order;
use App\Services\Exchange\Enums\SideEnum;
use App\Services\Exchange\Enums\TypeEnum;
use Illuminate\Support\Str;

class OrderService
{
    public static function openOrder(Coin $coin, mixed $currentPrice, TypeEnum $typeEnum, SideEnum $sideEnum, ?string $sl = null, ?string $tp = null, ?string $leverage = null): Order
    {
        $pendingOrder = Order::query()->create([
            'symbol' => $coin->symbol('-'),
            'coin_name' => $coin->name,
            'side' => Str::of($sideEnum->value)->upper()->toString(),
            'type' => Str::of($typeEnum->value)->upper()->toString(),
            'status' => Str::of(OrderStatusEnum::ONLY_CREATED->value)->upper()->toString(),
            'price' => $currentPrice,
            'sl' => $sl,
            'tp' => $tp,
        ]);

        event(new PendingOrderCreated($pendingOrder));

        return $pendingOrder;
    }
}
