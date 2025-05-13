<?php

namespace App\Services\Exchange\Coinex\Responses;

use App\Services\Exchange\Repository\Position;
use App\Services\Exchange\Responses\PositionResponseContract;

class PositionHistoryResponseAdapter extends PositionResponseAdapter
{
    private string $positionId;

    public function __construct(array $response, string $positionId)
    {
        parent::__construct($response);
        $this->positionId = $positionId;
    }

    public function position(): ?Position
    {
        if (count($this->response['data']) == 0){
            return null;
        }


        $filteredItem = collect($this->response['data'])
            ->filter(fn($item) => $item['position_id'] == $this->positionId)
            ->first();

        if ($filteredItem == null){
            return null;
        }

        $item = $this->convertDataToDTO($filteredItem);

        return Position::create($item);
    }
}
