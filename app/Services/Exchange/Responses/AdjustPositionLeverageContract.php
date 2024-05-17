<?php

namespace App\Services\Exchange\Responses;

interface AdjustPositionLeverageContract
{
    public function margin_mode(): string;
    public function leverage(): int;
}
