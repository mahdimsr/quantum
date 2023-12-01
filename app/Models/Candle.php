<?php

namespace App\Models;

use App\Enums\CandleCoinEnum;
use App\Enums\CandleTimeframeEnum;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int id
 * @property CandleCoinEnum coin
 * @property CandleTimeframeEnum timeframe
 * @property string high
 * @property string low
 * @property string open
 * @property string close
 * @property Carbon created_at
 * @property Carbon updated_at
 * @property Carbon deleted_at
 */
class Candle extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    protected $casts = [
        'coin' => CandleCoinEnum::class,
        'timeframe' => CandleTimeframeEnum::class,
    ];
}
