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

    public function time(): mixed
    {
        return $this->response['time'];
    }

    public function open(): mixed
    {
        return $this->response['open'];
    }

    public function high(): mixed
    {
        return $this->response['high'];
    }

    public function low(): mixed
    {
        return $this->response['low'];
    }

    public function close(): mixed
    {
        return $this->response['close'];
    }

    public function volume(): mixed
    {
        return $this->response['volume'];
    }

    public function error(): string
    {
        return 'Error Not implemented';
    }
}
