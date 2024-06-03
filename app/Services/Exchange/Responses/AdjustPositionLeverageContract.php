<?php

namespace App\Services\Exchange\Responses;

interface AdjustPositionLeverageContract
{
    public function isSuccess(): bool;
    public function message(): string;
    public function margin_mode(): string;
    public function leverage(): int;
}
