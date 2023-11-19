<?php

namespace App\Services\Exchange\Nobitex\Responses;

use App\Services\Exchange\Responses\StatsResponseContract;

class StatsResponse implements StatsResponseContract
{
    protected array $response;

    public function __construct(array $response)
    {
        $this->response = $response;
    }

    public function bestSell(): string
    {
        return $this->response['bestSell'];
    }

    public function isClosed(): bool
    {
        return $this->response['isClosed'];
    }

    public function dayOpen(): string
    {
        return $this->response['dayOpen'];
    }

    public function dayHigh(): string
    {
        return $this->response['dayHigh'];
    }

    public function bestBuy(): string
    {
        return $this->response['bestBuy'];
    }

    public function volumeSrc(): string
    {
        return $this->response['volumeSrc'];
    }

    public function dayLow(): string
    {
        return $this->response['dayLow'];
    }

    public function latest(): string
    {
        return $this->response['latest'];
    }

    public function volumeDst(): string
    {
        return $this->response['volumeDst'];
    }

    public function dayChange(): string
    {
        return $this->response['dayChange'];
    }

    public function dayClose(): string
    {
        return $this->response['dayClose'];
    }
}
