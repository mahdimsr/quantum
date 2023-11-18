<?php

namespace App\Services\Exchange\Nobitex\Responses;

use App\Services\Exchange\Responses\OrderResponseContract;

class OrderResponse implements OrderResponseContract
{
    protected array $response;

    public function __construct(array $response)
    {
        $this->response = $response;
    }

    public function lastUpdate(): int
    {
        // TODO: Implement lastUpdate() method.
    }

    public function bids(): array
    {
        // TODO: Implement bids() method.
    }

    public function asks(): array
    {
        // TODO: Implement asks() method.
    }
}
