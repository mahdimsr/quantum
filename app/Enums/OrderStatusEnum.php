<?php

namespace App\Enums;

enum OrderStatusEnum: string
{
    case ONLY_CREATED = 'ONLY_CREATED';
    case PENDING = 'PENDING';
    case CLOSED = 'CLOSED';
    case FAILED = 'FAILED';
}
