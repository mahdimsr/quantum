<?php

namespace App\Services\Exchange\BingX\Response;

use App\Enums\PositionTypeEnum;
use App\Enums\PriceTypeEnum;
use App\Services\Exchange\Repository\Position;
use App\Services\Exchange\Responses\PositionResponseContract;

class PositionResponseAdapter extends BingXResponse implements PositionResponseContract
{
    private array $symbolDataObject;

    public function __construct(array $response)
    {
        parent::__construct($response);

        $this->symbolDataObject = $this->response['data'];
    }

    public function position(): ?Position
    {
        $this->symbolDataObject['realizedProfit'] = $this->symbolDataObject['realisedProfit'];

        return Position::create($this->symbolDataObject);
    }
}
