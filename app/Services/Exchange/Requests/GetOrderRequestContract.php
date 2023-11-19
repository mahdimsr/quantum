<?php

namespace App\Services\Exchange\Requests;

use App\Services\Exchange\Responses\AllOrdersResponseContract;
use App\Services\Exchange\Responses\GetOrderResponseContract;

interface GetOrderRequestContract
{
    public function orders(): AllOrdersResponseContract;

    public function order(string $coinName): GetOrderResponseContract;
}
