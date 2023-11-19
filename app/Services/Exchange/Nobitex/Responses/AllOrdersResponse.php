<?php

namespace App\Services\Exchange\Nobitex\Responses;

use App\Services\Exchange\Responses\AllOrdersResponseContract;
use App\Services\Exchange\Responses\GetOrderResponseContract;

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

    public function coin(string $coinName): GetOrderResponseContract
    {
        return new GetOrderResponse($this->response[$coinName]);
    }
}
