<?php

namespace App\Models;

use App\Enums\ExchangeEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int id
 * @property int user_id
 * @property string exchange
 * @property string name
 * @property string value
 */
class UserToken extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    protected $casts = [
        'exchange' => ExchangeEnum::class,
    ];
}
