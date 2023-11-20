<?php

namespace App\Services\Exchange\Enums;

enum OrderExecutionEnum: string
{
    case MARKET = 'market';
    case LIMIT = 'limit';
}
