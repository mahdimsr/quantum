<?php

namespace App\Services\Exchange\Responses;

use App\Enums\PositionTypeEnum;
use App\Enums\PriceTypeEnum;

interface RewardResponseContract
{
    public function isSuccess(): bool;

    public function message(): string;

    public function positionId(): mixed;

    public function symbol(): string;

    public function positionType(): PositionTypeEnum;

    public function price(): mixed;

    public function priceType(): PriceTypeEnum;
}
