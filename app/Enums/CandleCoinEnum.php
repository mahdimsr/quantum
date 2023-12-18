<?php

namespace App\Enums;

enum CandleCoinEnum: int
{
    case BTC = 1;

    public function IRTSymbol(): string
    {
        return $this->name . 'IRT';
    }

    public function USDTSymbol(): string
    {
        return $this->name . 'USDT';
    }
}
