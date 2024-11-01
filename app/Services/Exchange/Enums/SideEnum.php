<?php

namespace App\Services\Exchange\Enums;


use Filament\Support\Contracts\HasColor;

enum SideEnum: string implements HasColor
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

    public function isLong(): bool
    {
        return $this == self::LONG or $this == self::BUY;
    }

    public function getColor(): string|array|null
    {
        return match ($this) {

            self::LONG, self::BUY => 'success',
            self::SHORT, self::SELL => 'danger',
        };
    }
}
