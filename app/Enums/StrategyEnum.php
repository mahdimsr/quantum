<?php

namespace App\Enums;

use App\Traits\OptionValues;

enum StrategyEnum: string
{
    use OptionValues;

    case SIMPLE_BOLLINGER_BAND = 'simple_bollinger_band';
    case UT_BOT_ALERT = 'ut_bot_alert';
}
