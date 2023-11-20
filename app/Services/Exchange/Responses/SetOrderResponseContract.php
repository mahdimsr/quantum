<?php

namespace App\Services\Exchange\Responses;

use App\Services\Exchange\Enums\OrderTypeEnum;

interface SetOrderResponseContract
{
    public function type(): OrderTypeEnum;

    public function srcCurrency(): string;

    public function dstCurrency(): string;

    public function price(): string;

    public function amount(): string;

    public function totalPrice(): string;

    public function matchedAmount(): int;

    public function unmatchedAmount(): string;

    public function id(): int;

    public function status(): string;

    public function partial(): bool;

    public function fee(): int;

    public function createdAt(): mixed;

    public function clientOrderId(): mixed;
}
