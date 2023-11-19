<?php

namespace App\Services\Exchange\Responses;

interface AllOrdersResponseContract
{
    public function all(): array;

    public function coin(string $coinName): GetOrderResponseContract;
}
