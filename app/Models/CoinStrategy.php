<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int id
 * @property int coin_id
 * @property string name
 */
class CoinStrategy extends Model
{
    public $timestamps = false;

    public function coin(): BelongsTo
    {
        return $this->belongsTo(Coin::class);
    }
}
