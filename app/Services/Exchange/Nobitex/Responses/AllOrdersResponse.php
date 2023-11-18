<?php

namespace App\Services\Exchange\Nobitex\Responses;

use App\Services\Exchange\Responses\AllOrdersResponseContract;
use App\Services\Exchange\Responses\OrderResponseContract;

class AllOrdersResponse implements AllOrdersResponseContract
{
    protected array $response;

    public function __construct(array $response)
    {
        $this->response = $response;
    }


    public function all(): array
    {
        return $this->response;
    }

    public function coin(string $coinName): OrderResponseContract
    {
        return new OrderResponse($this->response[$coinName]);
    }
}
