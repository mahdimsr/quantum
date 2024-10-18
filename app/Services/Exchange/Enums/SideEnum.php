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

    public function convertToBuySell(): string
    {
        return match ($this) {
            self::LONG, self::BUY => self::BUY->value,
            self::SHORT, self::SELL => self::SELL->value,
        };
    }

    public function isShort(): bool
    {
        return $this == self::SHORT or $this == self::SELL;
    }

    public function isLONG(): bool
    {
        return $this == self::LONG or $this == self::BUY;
    }
}
