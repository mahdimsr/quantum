<?php

namespace App\Services\Exchange\Bingx\Response;

use App\Services\Exchange\Repository\Order;
use App\Services\Exchange\Responses\SetOrderResponseContract;

class SetOrderResponseAdapter implements SetOrderResponseContract
{
    protected array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function isSuccess(): bool
    {
        return $this->data['code'] == 0;
    }

    public function message(): string
    {
        return $this->data['msg'];
    }

    public function order(): ?Order
    {
        dd($this->data['data']['order']);
    }
}
