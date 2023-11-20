<?php

namespace App\Services\Exchange\Requests;

use App\Services\Exchange\Enums\OrderTypeEnum;
use App\Services\Exchange\Enums\OrderExecutionEnum;
use App\Services\Exchange\Responses\AllOrdersResponseContract;
use App\Services\Exchange\Responses\GetOrderResponseContract;
use App\Services\Exchange\Responses\SetOrderResponseContract;

interface OrderRequestContract
{
    public function orders(): AllOrdersResponseContract;

    public function order(string $coinName): GetOrderResponseContract;

    public function setOrder(OrderTypeEnum $orderBuyEnum, OrderExecutionEnum $orderExecutionEnum, string $srcCurrency, string $dstCurrency, string $amount, string $price, string $clientOrderId): SetOrderResponseContract;
}
