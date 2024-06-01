<?php

namespace App\Services\Exchange\Coinex\Responses;

use App\Enums\PositionTypeEnum;
use App\Enums\PriceTypeEnum;
use App\Services\Exchange\Responses\PositionResponseContract;

class PositionResponseAdapter extends ResponseAdapter implements PositionResponseContract
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

    public function stopLossPrice(): mixed
    {
        return $this->data()['stop_loss_price'];
    }

    public function stopLossPriceType(): PriceTypeEnum
    {
        return $this->data()['stop_loss_type'] == 'mark_price' ? PriceTypeEnum::MARK : PriceTypeEnum::LATEST;
    }

    public function takeProfitPrice(): mixed
    {
        return $this->data()['take_profit_price'];
    }

    public function takeProfitPriceType(): PriceTypeEnum
    {
        return $this->data()['take_profit_type'] == 'mark_price' ? PriceTypeEnum::MARK : PriceTypeEnum::LATEST;
    }

    public function averageEntryPrice()
    {
        return $this->data()['avg_entry_price'];
    }
}
