<?php

namespace App\Services\Exchange\BingX\Response;

use App\Services\Exchange\Responses\SetLeverageResponseContract;

class SetLeverageResponseAdapter extends BingXResponse implements SetLeverageResponseContract
{

    public function leverage(): mixed
    {
        return $this->response['data']['leverage'];
    }
}
