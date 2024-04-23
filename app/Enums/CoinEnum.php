<?php

namespace App\Enums;

enum CoinEnum: string
{
    case BTC = 'BTC';

    public function IRTSymbol(): string
    {
        return $this->name . 'IRT';
    }

    public function USDTSymbol(): string
    {
        return $this->name . 'USDT';
    }
}
