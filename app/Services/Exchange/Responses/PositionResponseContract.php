<?php

namespace App\Services\Exchange\Responses;

use App\Enums\PositionTypeEnum;
use App\Enums\PriceTypeEnum;

interface PositionResponseContract
{
    public function isSuccess(): bool;

    public function message(): string;

    public function positionId(): mixed;

    public function symbol(): string;

    public function positionType(): PositionTypeEnum;

    public function stopLossPrice(): mixed;

    public function stopLossPriceType(): PriceTypeEnum;

    public function takeProfitPrice(): mixed;

    public function takeProfitPriceType(): PriceTypeEnum;

    public function averageEntryPrice();
}
