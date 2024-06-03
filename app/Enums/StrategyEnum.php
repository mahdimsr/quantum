<?php

namespace App\Enums;

use App\Traits\OptionValues;

enum StrategyEnum: int
{
    use OptionValues;

    case STATIC_REWARD = 1;
}
