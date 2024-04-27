<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int id
 * @property string name
 * @property float percent_tolerance
 */
class Coin extends Model
{
    public static function findByName(string $name): Model
    {
        return self::query()->where('name',$name)->firstOrFail();
    }

    public function USDTSymbol(): string
    {
        return $this->name.'USDT';
    }
}
