<?php

namespace App\Services\Exchange\Responses;

interface OHLCResponseContract
{
    public function status(): string;

    public function time(): mixed;

    public function open(): mixed;

    public function high(): mixed;

    public function low(): mixed;

    public function close(): mixed;

    public function volume(): mixed;

    public function error(): string;
}
