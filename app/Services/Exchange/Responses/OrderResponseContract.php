<?php

namespace App\Services\Exchange\Responses;

use App\Services\Exchange\Repository\Order;

interface OrderResponseContract
{
    public function isSuccess(): bool;
    public function message(): string;
    public function order(): ?Order;
}
