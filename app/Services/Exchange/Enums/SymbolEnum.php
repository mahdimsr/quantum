<?php

namespace App\Services\Exchange\Enums;

enum SymbolEnum : string
{
    case BTC = 'BTC';
    case ETH = 'ETH';

    public function toUSDT(): string
    {
        return $this->value . 'USDT';
    }
}
