<?php

namespace App\Enums;

enum CoinEnum: string
{
    case BTC = 'BTC';
    case FTM = 'FTM';
    case TON = 'TON';

    public function IRTSymbol(): string
    {
        return $this->name . 'IRT';
    }

    public function USDTSymbol(): string
    {
        return $this->name . 'USDT';
    }
}
