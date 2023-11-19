<?php

namespace App\Services\Exchange\Responses;

interface GetOrderResponseContract
{
    public function lastUpdate(): int;

    public function lastTradePrice(): int;

    public function bids(): array;

    public function asks(): array;
}
