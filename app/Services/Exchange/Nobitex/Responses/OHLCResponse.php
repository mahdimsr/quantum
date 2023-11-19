<?php

namespace App\Services\Exchange\Nobitex\Responses;

use App\Services\Exchange\Responses\OHLCResponseContract;

class OHLCResponse implements OHLCResponseContract
{
    protected array $response;

    public function __construct(array $response) {

        $this->response = $response;
    }

    public function status(): string
    {
        return $this->response['s'];
    }

    public function time(): array
    {
        return $this->response['t'];
    }

    public function open(): array
    {
        return $this->response['o'];
    }

    public function high(): array
    {
        return $this->response['h'];
    }

    public function low(): array
    {
        return $this->response['l'];
    }

    public function close(): array
    {
        return $this->response['c'];
    }

    public function volume(): array
    {
        return $this->response['v'];
    }

    public function error(): string
    {
        return $this->response['errmsg'];
    }
}
