<?php

namespace App\Services\Exchange\Responses;

interface CandleResponseContract
{
    public function code(): int;
    public function message(): string;
    public function data(): array;
}
