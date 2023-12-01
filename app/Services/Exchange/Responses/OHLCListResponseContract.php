<?php

namespace App\Services\Exchange\Responses;

interface OHLCListResponseContract
{
    public function status(): string;

    public function ohlc(mixed $index): OHLCResponseContract;

    public function error(): string;
}
