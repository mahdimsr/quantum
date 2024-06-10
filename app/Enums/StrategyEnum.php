<?php

namespace App\Enums;

use App\Traits\OptionValues;

enum StrategyEnum: int
{
    use OptionValues;

    case STATIC_REWARD = 1;
    case NO_STRATEGY = 2;
    case WEEKLY_REWARD = 3;
}
