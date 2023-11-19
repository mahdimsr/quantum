<?php

namespace App\Services\Exchange\Nobitex\Responses;

use App\Services\Exchange\Responses\GetOrderResponseContract;

class GetOrderResponse implements GetOrderResponseContract
{
    protected array $response;

    public function __construct(array $response)
    {
        $this->response = $response;
    }

    public function lastUpdate(): int
    {
        return $this->response['lastUpdate'];
    }

    public function lastTradePrice(): int
    {
        return $this->response['lastTradePrice'];
    }

    public function bids(): array
    {
        return $this->response['bids'];
    }

    public function asks(): array
    {
        return $this->response['asks'];
    }
}
