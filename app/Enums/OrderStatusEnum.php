<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;

enum OrderStatusEnum: string implements HasColor
{
    case ONLY_CREATED = 'ONLY_CREATED';
    case PENDING = 'PENDING';
    case CLOSED = 'CLOSED';
    case FAILED = 'FAILED';

    public function getColor(): string|array|null
    {
        return match ($this) {
          self::ONLY_CREATED => 'gray',
          self::PENDING => 'info',
          self::FAILED => 'danger',
          self::CLOSED => 'success',
        };
    }
}
