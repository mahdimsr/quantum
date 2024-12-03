<?php

namespace App\Services\Exchange\Coinex\Responses;

use App\Services\Exchange\Responses\ClosePositionResponseContract;

class ClosePositionResponseAdapter extends BaseResponse implements ClosePositionResponseContract
{

    public function order_id(): string
    {
        return $this->response['data']['client_id'];
    }

    public function position_id(): string
    {
        return $this->response['data']['client_id'];
    }
}
