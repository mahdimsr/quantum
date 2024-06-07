<?php

namespace App\Enums;

use App\Traits\OptionValues;

enum CoinStatusEnum: int
{
    use OptionValues;

    case DISABLE = 0;
    case AVAILABLE = 1;
}
