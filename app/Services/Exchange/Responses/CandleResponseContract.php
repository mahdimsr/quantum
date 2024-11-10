<?php

namespace App\Services\Exchange\Responses;

use App\Services\Exchange\Repository\CandleCollection;

interface CandleResponseContract
{
    public function code(): int;
    public function message(): string;

    public function isSuccess(): bool;
    public function data(): CandleCollection;
}
