<?php

namespace App\Services\Exchange\Coinex\Responses;

use App\Services\Exchange\Repository\Position;
use App\Services\Exchange\Responses\PositionResponseContract;

class PositionResponseAdapter extends BaseResponse implements PositionResponseContract
{

    public function position(): ?Position
    {
        $data = $this->response['data'][0];

        $item = [];

        $item['positionId'] = $data['position_id'];
        $item['symbol'] = $data['market'];
        $item['unrealizedProfit'] = $data['unrealized_pnl'];
        $item['realizedProfit'] = $data['realized_pnl'];
        $item['markPrice'] = $data['avg_entry_price'];
        $item['pnlRatio'] = $this->calculatePnlRation($data['unrealized_pnl'], $data['margin_avbl']);

        return Position::create($item);
    }

    private function calculatePnlRation(mixed $unrealizedPnl, mixed $availableMargin): mixed
    {
        return (floatval($unrealizedPnl) / floatval($availableMargin)) * 100;
    }
}
