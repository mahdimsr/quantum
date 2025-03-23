<?php

namespace App\Services\Exchange\Coinex\Responses;

abstract class BaseResponse
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

    public function isSuccess(): bool
    {
        return $this->message() == 'OK' or $this->code() == 0;
    }
}
