<?php

namespace App\Services\Exchange\Bitunix\Responses;

use App\Services\Exchange\Repository\Position;
use App\Services\Exchange\Responses\PositionResponseContract;

class PositionHistoryResponseAdapter extends BaseResponse implements PositionResponseContract
{
    private string $positionId;

    public function __construct(array $response, string $positionId)
    {
        parent::__construct($response);
        $this->positionId = $positionId;
    }

    public function position(): ?Position
    {
        $data = $this->response['data'];
        
        // Find the specific position by ID
        $positionData = collect($data)->filter(function ($item) {
            return $item['positionId'] == $this->positionId;
        })->first();

        if (!$positionData) {
            return null;
        }

        $item = $this->convertDataToDTO($positionData);

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
