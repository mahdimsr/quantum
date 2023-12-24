<?php

namespace App\Services\Exchange\Enums;

enum SymbolEnum : string
{
    case BTC = 'BTC';
    case ETH = 'ETH';
    case FTM = 'FTM';
    case KSM = 'KSM';
    case SCRT = 'SCRT';
    case MINA = 'MINA';

    public function toUSDT(): string
    {
        return $this->value . 'USDT';
    }
}
