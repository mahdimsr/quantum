<?php

namespace App\Services\Exchange\Responses;

use App\Services\Exchange\Repository\OrderCollection;

interface OrderListResponseContract
{
    public function code(): int;
    public function message(): string;
    public function data(): OrderCollection;
}
