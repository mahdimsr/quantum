<?php

namespace App\Services\Exchange\Requests;

use App\Services\Exchange\Responses\AllOrdersResponseContract;
use App\Services\Exchange\Responses\OrderResponseContract;

interface OrderRequestContract
{
    public function orders(): AllOrdersResponseContract;

    public function order(string $coinName): OrderResponseContract;
}
