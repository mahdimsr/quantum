<?php

namespace App\Services\Exchange\Enums;

enum TypeEnum: string
{
    case LIMIT = 'LIMIT';
    case MARKET = 'MARKET';
    case STOP_MARKET = 'STOP_MARKET';
    case TAKE_PROFIT_MARKET = 'TAKE_PROFIT_MARKET';
    case STOP = 'STOP';
    case TAKE_PROFIT = 'TAKE_PROFIT';
    case TRIGGER_LIMIT = 'TRIGGER_LIMIT';
    case TRIGGER_MARKET = 'TRIGGER_MARKET';
    case TRAILING_STOP_MARKET = 'TRAILING_STOP_MARKET';
    case TRAILING_TP_SL = 'TRAILING_TP_SL';
}
