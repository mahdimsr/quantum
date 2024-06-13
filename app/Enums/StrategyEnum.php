<?php

namespace App\Enums;

use App\Traits\OptionValues;

enum StrategyEnum: string
{
    use OptionValues;

    case NO_STRATEGY = 'no_strategy';
    case BOLLINGER_BAND = 'bollinger_band';
    case UT_BOT_ALERT = 'ut_bot_alert';
}
