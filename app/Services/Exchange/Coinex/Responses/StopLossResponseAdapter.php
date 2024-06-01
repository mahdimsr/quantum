<?php

namespace App\Services\Exchange\Coinex\Responses;

use App\Enums\PositionTypeEnum;
use App\Enums\PriceTypeEnum;
use App\Services\Exchange\Responses\RewardResponseContract;

class StopLossResponseAdapter extends ResponseAdapter implements RewardResponseContract
{
    public function positionId(): mixed
    {
        return $this->data()['position_id'];
    }

    public function symbol(): string
    {
        return $this->data()['market'];
    }

    public function positionType(): PositionTypeEnum
    {
        return PositionTypeEnum::fromValue($this->data()['side']);
    }

    public function price(): mixed
    {
        return $this->data()['stop_loss_price'];
    }

    public function priceType(): PriceTypeEnum
    {
        return $this->data()['stop_loss_type'] == 'mark_price' ? PriceTypeEnum::MARK : PriceTypeEnum::LATEST;
    }
}
