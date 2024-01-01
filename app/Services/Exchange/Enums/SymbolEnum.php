<?php

namespace App\Services\Exchange\Enums;

enum SymbolEnum: string
{
    case BTC = 'BTC';
    case ETH = 'ETH';
    case FTM = 'FTM';
    case AAVE = 'AAVE';
    case ADA = 'ADA';

    public function toUSDT(): string
    {
        return $this->value . 'USDT';
    }
}
