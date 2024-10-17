<?php

namespace App\Services\Exchange\Responses;

interface ClosePositionResponseContract
{
    public function isSuccess(): bool;
    public function message(): string;
    public function order_id(): string;
    public function position_id(): string;

}
