<?php

namespace App\Services\Exchange\Responses;

interface OrderResponseContract
{
    public function lastUpdate(): int;

    public function bids(): array;

    public function asks(): array;
}
