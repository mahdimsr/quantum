<?php

namespace App\Services\Exchange\Responses;

use Illuminate\Support\Collection;

interface OHLCListResponseContract
{
    public function status(): string;

    public function ohlc(mixed $index): OHLCResponseContract;

    public function count(): int;

    public function error(): string;

    public function all(): Collection;
}
