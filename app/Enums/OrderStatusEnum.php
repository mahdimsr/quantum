<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;

enum OrderStatusEnum: string implements HasColor
{
    case ONLY_CREATED = 'ONLY_CREATED';
    case OPEN = 'OPEN';
    case CLOSED = 'CLOSED';
    case FAILED = 'FAILED';
    case UNKNOWN = 'UNKNOWN';

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::ONLY_CREATED => 'gray',
            self::OPEN => 'info',
            self::FAILED => 'danger',
            self::UNKNOWN => 'warning',
            self::CLOSED => 'success',
        };
    }
}
