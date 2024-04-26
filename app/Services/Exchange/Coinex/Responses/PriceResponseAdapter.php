<?php

namespace App\Services\Exchange\Coinex\Responses;

use App\Services\Exchange\Responses\CurrentResponseContract;

class PriceResponseAdapter implements CurrentResponseContract
{
    private array $response;

    public function __construct(array $response)
    {
        $this->response = $response['data'][0];
    }

    public function symbol(): string
    {
        return $this->response['market'];
    }

    public function volume(): string
    {
        return $this->response['volume'];
    }

    public function indexPrice(): string
    {
        return $this->response['indexPrice'];
    }

    public function markPrice(): string
    {
        return $this->response['markPrice'];
    }
}
