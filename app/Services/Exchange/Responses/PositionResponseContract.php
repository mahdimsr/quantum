<?php

namespace App\Services\Exchange\Responses;

use App\Enums\PositionTypeEnum;
use App\Enums\PriceTypeEnum;
use App\Services\Exchange\Repository\Position;

interface PositionResponseContract
{
    public function isSuccess(): bool;

    public function message(): string;

    public function position(): ?Position;
}
