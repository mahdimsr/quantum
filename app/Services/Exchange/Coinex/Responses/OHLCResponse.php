<?php

namespace App\Services\Exchange\Coinex\Responses;

use App\Services\Exchange\Responses\OHLCResponseContract;

class OHLCResponse implements OHLCResponseContract
{
    private array $data;

    public function __construct(array $data) {

        $this->data = $data;
    }

    public function status(): string
    {
        // TODO: Implement status() method.
    }

    public function time(): mixed
    {
        // TODO: Implement time() method.
    }

    public function open(): mixed
    {
        return $this->data[1];
    }

    public function high(): mixed
    {
        return $this->data[3];
    }

    public function low(): mixed
    {
        return $this->data[4];
    }

    public function close(): mixed
    {
        return $this->data[2];
    }

    public function volume(): mixed
    {
        return $this->data[5];
    }

    public function error(): string
    {
        // TODO: Implement error() method.
    }
}
