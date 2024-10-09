<?php

namespace App\Services\Exchange\Nobitex\Responses;

use App\Services\Exchange\Enums\SideEnum;
use App\Services\Exchange\Responses\SetOrderResponseContract;

class SetOrderResponse implements SetOrderResponseContract
{
    protected array $response;

    public function __construct(array $response) {

        $this->response = $response;
    }


    public function type(): SideEnum
    {
        return SideEnum::from($this->response['order']['type']);
    }

    public function srcCurrency(): string
    {
        return $this->response['order']['srcCurrency'];
    }

    public function dstCurrency(): string
    {
        return $this->response['order']['dstCurrency'];
    }

    public function price(): string
    {
        return $this->response['order']['price'];
    }

    public function amount(): string
    {
        return $this->response['order']['amount'];
    }

    public function totalPrice(): string
    {
        return $this->response['order']['totalPrice'];
    }

    public function matchedAmount(): int
    {
        return $this->response['order']['matchedAmount'];
    }

    public function unmatchedAmount(): string
    {
        return $this->response['order']['unmatchedAmount'];
    }

    public function id(): int
    {
        return $this->response['order']['id'];
    }

    public function status(): string
    {
        return $this->response['order']['status'];
    }

    public function partial(): bool
    {
        return $this->response['order']['partial'];
    }

    public function fee(): int
    {
        return $this->response['order']['fee'];
    }

    public function createdAt(): mixed
    {
        return $this->response['order']['createdAt'];
    }

    public function clientOrderId(): mixed
    {
        return $this->response['order']['clientOrderId'];
    }
}
