<?php

namespace App\Services\Exchange\Requests;

use App\Services\Exchange\Enums\SideEnum;
use App\Services\Exchange\Enums\TypeEnum;
use App\Services\Exchange\Repository\Target;
use App\Services\Exchange\Responses\OrderListResponseContract;
use App\Services\Exchange\Responses\SetOrderResponseContract;

interface OrderRequestContract
{
    public function orders(?string $symbol = null, ?array $orderIds = null): OrderListResponseContract;
    public function setOrder(string $symbol, TypeEnum $typeEnum, SideEnum $sideEnum, SideEnum $positionSide, float $amount, float $price, mixed $client_id = null, ?Target $takeProfit = null, ?Target $stopLoss = null): ?SetOrderResponseContract;
}
