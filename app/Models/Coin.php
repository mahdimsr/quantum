<?php

namespace App\Models;

use App\Enums\CoinStatusEnum;
use App\Enums\StrategyEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int id
 * @property string name
 * @property float percent_tolerance
 * @property int leverage
 * @property CoinStatusEnum status
 * @property int order
 * @property StrategyEnum strategy_type
 *
 * @method static Builder strategy(StrategyEnum $strategyEnum)
 * @method static Builder status(CoinStatusEnum $coinStatusEnum)
 */
class Coin extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'strategy_type' => StrategyEnum::class,
        'status' => CoinStatusEnum::class,
    ];

    public function scopeStrategy(Builder $builder, StrategyEnum $strategyEnum)
    {
        $builder->where('strategy_type', $strategyEnum->value);
    }

    public function scopeStatus(Builder $builder, CoinStatusEnum $coinStatusEnum)
    {
        $builder->where('status', $coinStatusEnum->value);
    }

    public static function findByName(string $name): Model|self
    {
        return self::query()->where('name',$name)->firstOrFail();
    }

    public function USDTSymbol(): string
    {
        return $this->name.'USDT';
    }
}
