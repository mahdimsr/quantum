<?php

namespace App\Services\Exchange\Enums;

enum ExchangeResolutionEnum: string
{
    case EVERY_MINUTE = '1';
    case EVERY_FIVE_MINUTES = '5';
    case EVERY_FIFTEEN_MINUTES = '15';
    case EVERY_THIRTY_MINUTES = '30';
    case EVERY_HOUR = '60';
    case EVERY_THREE_HOURS = '180';
    case EVERY_FOUR_HOURS = '240';
    case EVERY_SIX_HOURS = '360';
    case EVERY_TWELVE_HOURS = '720';
    case DAILY = 'D';
    case TWO_DAYS = '2D';
    case THREE_DAYS = '3D';
}
