<?php

namespace App\Services\Exchange\Requests;

use App\Services\Exchange\Enums\OrderTypeEnum;
use App\Services\Exchange\Enums\OrderExecutionEnum;
use App\Services\Exchange\Responses\AllOrdersResponseContract;
use App\Services\Exchange\Responses\GetOrderResponseContract;
use App\Services\Exchange\Responses\OrderResponseContract;
use App\Services\Exchange\Responses\SetOrderResponseContract;

interface OrderRequestContract
{
    public function orders(string $marketType): ?OrderResponseContract;
}
