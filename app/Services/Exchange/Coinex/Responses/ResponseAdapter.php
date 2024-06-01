<?php

namespace App\Services\Exchange\Coinex\Responses;

abstract class ResponseAdapter
{
    protected array $response;

    public function __construct(array $response)
    {
        $this->response = $response;
    }

    public function code(): int
    {
        return $this->response['code'];
    }

    public function message(): string
    {
        return $this->response['message'];
    }

    public function data(): array
    {
        return $this->response['data'];
    }

    public function isSuccess(): bool
    {
        return $this->code() == 0;
    }
}
