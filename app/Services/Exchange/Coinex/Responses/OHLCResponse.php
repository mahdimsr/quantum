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
        // TODO: Implement open() method.
    }

    public function high(): mixed
    {
        // TODO: Implement high() method.
    }

    public function low(): mixed
    {
        // TODO: Implement low() method.
    }

    public function close(): mixed
    {
        // TODO: Implement close() method.
    }

    public function volume(): mixed
    {
        // TODO: Implement volume() method.
    }

    public function error(): string
    {
        // TODO: Implement error() method.
    }
}
