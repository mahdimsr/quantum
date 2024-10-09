<?php

namespace App\Services\Exchange\Enums;


enum SideEnum: string
{
    case LONG = 'long';
    case BUY = 'buy';
    case SELL = 'sell';
    case SHORT = 'short';
}
