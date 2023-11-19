<?php

namespace App\Services\Exchange\Nobitex\Responses;

use App\Services\Exchange\Responses\UserResponseContract;
use JetBrains\PhpStorm\ArrayShape;

class UserResponse implements UserResponseContract
{
    protected array $response;

    public function __construct(array $response)
    {
        $this->response = $response;
    }

    public function firstName(): string
    {
        return $this->response['firstName'];
    }

    public function lastName(): string
    {
        return $this->response['lastName'];
    }

    public function nationalCode(): string
    {
        return $this->response['nationalCode'];
    }

    public function email(): string
    {
        return $this->response['email'];
    }

    public function username(): string
    {
        return $this->response['username'];
    }

    public function phone(): string
    {
        return $this->response['phone'];
    }

    public function mobile(): string
    {
        return $this->response['mobile'];
    }

    public function city(): string
    {
        return $this->response['city'];
    }

    public function rialFee(): string
    {
        return $this->response['options']['fee'];
    }

    public function usdtFee(): string
    {
        return $this->response['options']['feeUsdt'];
    }

    #[ArrayShape(['monthTradesTotal' => "mixed", 'monthTradesCount' => "mixed"])]
    public function extra(): array
    {
        return [
            'monthTradesTotal' => $this->response['tradeStats']['monthTradesTotal'],
            'monthTradesCount' => $this->response['tradeStats']['monthTradesCount'],
        ];
    }
}
