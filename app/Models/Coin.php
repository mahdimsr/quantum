<?php

namespace App\Models;

use App\Enums\CoinStatusEnum;
use App\Enums\StrategyEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * @property int id
 * @property string name
 * @property float percent_tolerance
 * @property int leverage
 * @property int fee
 * @property CoinStatusEnum status
 * @property int order
 * @property array strategies
 *
 * @method static Builder strategy(StrategyEnum $strategyEnum)
 * @method static Builder status(CoinStatusEnum $coinStatusEnum)
 */
class Coin extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'strategies' => 'array',
        'status' => CoinStatusEnum::class,
    ];

    public function scopeStrategy(Builder $builder, StrategyEnum $strategyEnum)
    {
        $builder->whereJsonContains('strategies', $strategyEnum->value);
    }

    public function scopeStatus(Builder $builder, CoinStatusEnum $coinStatusEnum)
    {
        $builder->where('status', $coinStatusEnum->value);
    }

    public function fee(): Attribute
    {
        return Attribute::make(
            get: fn(?string $value) => Str::of($value)->toFloat(),
        );
    }

    public static function findByName(string $name): Model|self
    {
        return self::query()->where('name', $name)->firstOrFail();
    }

    public function symbol(?string $separator = null, string $symbol = 'USDT'): string
    {
        if ($separator) {

            return $this->name . $separator . $symbol;
        }

        return $this->name . $symbol;
    }
}
