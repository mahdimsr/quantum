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
        return 'Status Not implemented';
    }

    public function time(): array
    {
        return $this->response['time'];
    }

    public function open(): array
    {
        return $this->response['open'];
    }

    public function high(): array
    {
        return $this->response['high'];
    }

    public function low(): array
    {
        return $this->response['low'];
    }

    public function close(): array
    {
        return $this->response['close'];
    }

    public function volume(): array
    {
        return $this->response['volume'];
    }

    public function error(): string
    {
        return 'Error Not implemented';
    }
}
