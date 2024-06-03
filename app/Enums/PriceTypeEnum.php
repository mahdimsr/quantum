<?php

namespace App\Enums;

enum PriceTypeEnum: string
{
    case MARK = 'mark_price';
    case LATEST = 'latest_price';
}
