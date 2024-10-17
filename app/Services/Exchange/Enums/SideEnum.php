<?php

namespace App\Services\Exchange\Enums;


enum SideEnum: string
{
    case LONG = 'LONG';
    case BUY = 'BUY';
    case SELL = 'SELL';
    case SHORT = 'SHORT';

    public function convertToLongShort(): string
    {
        return match ($this) {
            self::LONG, self::BUY => self::LONG->value,
            self::SHORT, self::SELL => self::SHORT->value,
        };
    }
}
