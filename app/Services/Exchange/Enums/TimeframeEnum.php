<?php

namespace App\Services\Exchange\Enums;

use Illuminate\Support\Str;

enum TimeframeEnum: string
{
    case EVERY_MINUTE = '1m';
    case EVERY_FIVE_MINUTES = '5m';
    case EVERY_FIFTEEN_MINUTES = '15m';
    case EVERY_THIRTY_MINUTES = '30m';
    case EVERY_HOUR = '1H';
    case EVERY_TWO_HOURS = '2H';
    case EVERY_FOUR_HOURS = '4H';
    case EVERY_SIX_HOURS = '6H';
    case EVERY_TWELVE_HOURS = '12H';
    case DAILY = '1D';
    case TWO_DAYS = '2D';
    case THREE_DAYS = '3D';
    case WEEKLY = '1W';

    public function toBingXFormat(): string
    {
        if (Str::endsWith($this->value,'H')) {

            return Str::replace('H', 'h', $this->value);
        }

        return $this->value;
    }
}
