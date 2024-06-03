<?php

namespace App\Enums;

enum PositionTypeEnum: string
{
    case LONG = 'long';
    case SHORT = 'short';

    public function convertToBuyOrSell(): string
    {
        return match ($this) {
            self::LONG  => 'buy',
            self::SHORT => 'sell',
        };
    }

    public static function fromValue(string $value): self
    {

        return match (strtolower($value)) {
            'long', 'buy'   => self::LONG,
            'short', 'sell' => self::SHORT,
        };
    }
}
