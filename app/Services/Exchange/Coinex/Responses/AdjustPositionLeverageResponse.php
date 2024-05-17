<?php

namespace App\Services\Exchange\Coinex\Responses;

use App\Services\Exchange\Responses\AdjustPositionLeverageContract;

class AdjustPositionLeverageResponse implements AdjustPositionLeverageContract
{
    private array $response;

    public function __construct(array $response)
    {
        $this->response = $response;
    }

    public function margin_mode(): string
    {
        return $this->response['data']['margin_mode'];
    }

    public function leverage(): int
    {
        return $this->response['data']['leverage'];
    }
}
