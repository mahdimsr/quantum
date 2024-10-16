<?php

namespace App\Enums;

enum OrderStatusEnum: string
{
    case PENDING = 'PENDING';
    case RUNNING = 'RUNNING';
    case DONE = 'DONE';
}
