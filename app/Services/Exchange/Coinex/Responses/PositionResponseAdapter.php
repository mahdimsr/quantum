<?php

namespace App\Services\Exchange\Coinex\Responses;

use App\Services\Exchange\Repository\Position;
use App\Services\Exchange\Responses\PositionResponseContract;

class PositionResponseAdapter extends BaseResponse implements PositionResponseContract
{

    public function position(): ?Position
    {
        if (count($this->response['data']) == 0){
            return null;
        }

        $data = $this->response['data'][0];

        $item = $this->convertDataToDTO($data);

        return Position::create($item);
    }

    protected function convertDataToDTO(array $positonResponseItem): array
    {
        $item = [];

        $item['positionId'] = $positonResponseItem['position_id'];
        $item['symbol'] = $positonResponseItem['market'];
        $item['unrealizedProfit'] = $positonResponseItem['unrealized_pnl'];
        $item['realizedProfit'] = $positonResponseItem['realized_pnl'];
        $item['markPrice'] = $positonResponseItem['avg_entry_price'];
        $item['pnlRatio'] = $this->calculatePnlRation($positonResponseItem['unrealized_pnl'], $positonResponseItem['margin_avbl']);

        return $item;
    }

    private function calculatePnlRation(mixed $unrealizedPnl, mixed $availableMargin): mixed
    {
        if ($availableMargin == 0){
            return 0;
        }
        return (floatval($unrealizedPnl) / floatval($availableMargin)) * 100;
    }
}
