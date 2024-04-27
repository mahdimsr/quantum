<?php

namespace App\Enums;

use Illuminate\Support\Str;

enum TimeframeEnum: string
{
    case EVERY_MINUTE = '1m';
    case EVERY_FIVE_MINUTES = '5m';
    case EVERY_FIFTEEN_MINUTES = '15m';
    case EVERY_THIRTY_MINUTES = '30m';
    case EVERY_HOUR = '1H';
    case EVERY_THREE_HOURS = '3H';
    case EVERY_FOUR_HOURS = '4H';
    case EVERY_SIX_HOURS = '6H';
    case EVERY_TWELVE_HOURS = '12H';
    case DAILY = '1D';
    case TWO_DAYS = '2D';
    case THREE_DAYS = '3D';

    public function toCoinexFormat(): string
    {
        if (Str::of($this->value)->endsWith('H')){
            return Str::of($this->value)->replace('H','hour');
        }

        if (Str::of($this->value)->endsWith('m')){
            return Str::of($this->value)->replace('m','min');
        }

        if (Str::of($this->value)->endsWith('D')){
            return Str::of($this->value)->replace('D','day');
        }

        return $this->value;
    }
}
