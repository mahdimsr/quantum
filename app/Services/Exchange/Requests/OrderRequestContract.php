<?php

namespace App\Services\Exchange\Requests;

use App\Services\Exchange\Enums\SideEnum;
use App\Services\Exchange\Enums\TypeEnum;
use App\Services\Exchange\Repository\Order;
use App\Services\Exchange\Repository\PositionLevelCollection;
use App\Services\Exchange\Responses\AllOrdersResponseContract;
use App\Services\Exchange\Responses\GetOrderResponseContract;
use App\Services\Exchange\Responses\OrderListResponseContract;
use App\Services\Exchange\Responses\OrderResponseContract;
use App\Services\Exchange\Responses\SetOrderResponseContract;

interface OrderRequestContract
{
    public function setOrder(string $symbol, TypeEnum $typeEnum, SideEnum $sideEnum, SideEnum $positionSide, float $amount, float $price): ?OrderResponseContract;
}
