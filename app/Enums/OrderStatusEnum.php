<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;

enum OrderStatusEnum: string implements HasColor
{
    case ONLY_CREATED = 'ONLY_CREATED';
    case MANUAL_CREATED = 'MANUAL_CREATED';
    case OPEN = 'OPEN';
    case HAS_TP = 'HAS Take Profit';
    case CLOSED = 'CLOSED';
    case FAILED = 'FAILED';
    case UNKNOWN = 'UNKNOWN';

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::ONLY_CREATED, self::MANUAL_CREATED => 'gray',
            self::OPEN, self::HAS_TP => 'info',
            self::FAILED => 'danger',
            self::UNKNOWN => 'warning',
            self::CLOSED => 'success',
        };
    }
}
