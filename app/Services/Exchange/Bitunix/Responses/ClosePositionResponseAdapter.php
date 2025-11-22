<?php

namespace App\Services\Exchange\Bitunix\Responses;

use App\Services\Exchange\Responses\ClosePositionResponseContract;

class ClosePositionResponseAdapter extends BaseResponse implements ClosePositionResponseContract
{

    public function order_id(): string
    {
        return $this->response['data']['orderId'] ?? '';
    }

    public function position_id(): string
    {
        return $this->response['data']['positionId'] ?? '';
    }
}
