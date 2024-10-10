<?php

namespace App\Services\Exchange\Enums;


enum SideEnum: string
{
    case LONG = 'LONG';
    case BUY = 'BUY';
    case SELL = 'SELL';
    case SHORT = 'SHORT';
}
