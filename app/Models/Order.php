<?php

namespace App\Models;

use App\Enums\OrderStatusEnum;
use App\Observers\OrderObserver;
use App\Services\Exchange\Enums\SideEnum;
use App\Services\Exchange\Enums\TypeEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int id
 * @property string client_id
 * @property string exchange
 * @property string exchange_order_id
 * @property string symbol
 * @property string coin_name
 * @property SideEnum side
 * @property TypeEnum type
 * @property OrderStatusEnum status
 * @property string price
 * @property string balance
 *
 * @property Coin coin
 *
 * @method static Builder status(OrderStatusEnum $orderStatusEnum)
 */
class Order extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'side' => SideEnum::class,
        'type' => TypeEnum::class,
        'status' => OrderStatusEnum::class,
    ];

    protected static function booted(): void
    {
        self::observe(OrderObserver::class);
    }

    public function scopeStatus(Builder $query, OrderStatusEnum $orderStatusEnum): void
    {
        $query->where('status', $orderStatusEnum->value);
    }

    public static function findByClientId(string $clientId): null|Order|Model
    {
        return self::query()->where('client_id', $clientId)->first();
    }

    public function coin()
    {
        return $this->hasOne(Coin::class, 'name', 'coin_name');
    }
}
