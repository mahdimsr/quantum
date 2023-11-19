<?php

namespace App\Services\Exchange\Responses;


interface UserResponseContract
{
    public function firstName(): string;

    public function lastName(): string;

    public function nationalCode(): string;

    public function email(): string;

    public function username(): string;

    public function phone(): string;

    public function mobile(): string;

    public function city(): string;

    public function rialFee(): string;

    public function usdtFee(): string;

    public function extra(): array;
}
