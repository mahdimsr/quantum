<?php

namespace App\Models;

use App\Enums\PositionTypeEnum;
use App\Observers\OrderObserver;
use App\Services\Exchange\Enums\SideEnum;
use App\Services\Exchange\Enums\TypeEnum;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int id
 * @property string client_id
 * @property string exchange
 * @property string symbol
 * @property string coin
 * @property SideEnum side
 * @property TypeEnum type
 * @property string status
 * @property string price
 */
class Order extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'side' => SideEnum::class,
        'type' => TypeEnum::class,
    ];

    protected static function booted(): void
    {
        self::observe(OrderObserver::class);
    }

    public function coin()
    {
        return $this->hasOne(Coin::class, 'name', 'coin');
    }
}
