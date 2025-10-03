<?php

namespace App\Services\Exchange\Bitunix\Responses;

use App\Services\Exchange\Repository\Position;
use App\Services\Exchange\Responses\PositionResponseContract;

class PositionResponseAdapter extends BaseResponse implements PositionResponseContract
{

    public function position(): ?Position
    {
        if (count($this->response['data']) == 0) {
            return null;
        }

        $data = $this->response['data'][0];

        $item = $this->convertDataToDTO($data);

        return Position::create($item);
    }

    protected function convertDataToDTO(array $positionResponseItem): array
    {
        $item = [];

        $item['positionId'] = $positionResponseItem['positionId'];
        $item['symbol'] = $positionResponseItem['symbol'];
        $item['unrealizedProfit'] = $positionResponseItem['unrealizedPnl'] ?? 0;
        $item['realizedProfit'] = $positionResponseItem['realizedPnl'] ?? 0;
        $item['markPrice'] = $positionResponseItem['avgPrice'] ?? 0;
        $item['pnlRatio'] = $this->calculatePnlRatio($positionResponseItem['unrealizedPnl'] ?? 0, $positionResponseItem['margin'] ?? 0);

        return $item;
    }

    private function calculatePnlRatio(mixed $unrealizedPnl, mixed $availableMargin): mixed
    {
        if ($availableMargin == 0) {
            return 0;
        }
        return (floatval($unrealizedPnl) / floatval($availableMargin)) * 100;
    }
}
