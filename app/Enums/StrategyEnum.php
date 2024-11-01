<?php

namespace App\Enums;

use App\Traits\OptionValues;

enum StrategyEnum: string
{
    use OptionValues;

    case Static_Profit = 'Static Profit';
    case DYNAMIC_REWARD = 'Dynamic Reward';

    public function description(): string
    {
        return match ($this) {

            self::Static_Profit => 'in static profit strategy, quantum looking for specific pnl percent and then close position, current pnl percentage profit is 1 percent',
            self::DYNAMIC_REWARD => 'it move sl dynamically behind the trend, and close position if reverse signal sent',
        };
    }
}
