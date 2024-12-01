<?php

namespace App\Services\Exchange\Coinex\Responses;

use App\Services\Exchange\Responses\SetLeverageResponseContract;

class SetLeverageResponseAdapter extends BaseResponse implements SetLeverageResponseContract
{

    public function leverage(): mixed
    {
        return $this->response['data']['leverage'];
    }
}
