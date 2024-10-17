<?php

namespace App\Services\Exchange\BingX\Response;

use App\Services\Exchange\Responses\ClosePositionResponseContract;

class ClosePositionResponseAdapter extends BingXResponse implements ClosePositionResponseContract
{

    public function order_id(): string
    {
        return $this->response['data']['orderId'];
    }

    public function position_id(): string
    {
        return $this->response['data']['positionId'];
    }
}
