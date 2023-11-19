<?php

namespace App\Services\Exchange\Responses;

interface OHLCResponseContract
{
    public function status(): string;

    public function time(): array;

    public function open(): array;

    public function high(): array;

    public function low(): array;

    public function close(): array;

    public function volume(): array;

    public function error(): string;
}
