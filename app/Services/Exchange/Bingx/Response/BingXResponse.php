<?php

namespace App\Services\Exchange\Bingx\Response;

abstract class BingXResponse
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
        return $this->response['msg'];
    }

    public function isSuccess(): bool
    {
        return $this->code() == 0;
    }
}
