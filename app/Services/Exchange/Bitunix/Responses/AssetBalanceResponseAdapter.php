<?php

namespace App\Services\Exchange\Bitunix\Responses;

use App\Services\Exchange\Responses\AssetBalanceContract;

class AssetBalanceResponseAdapter extends BaseResponse implements AssetBalanceContract
{

    public function balance(): mixed
    {
        $data = $this->response['data'];

        $usdtBalance = $data['marginCoin'];

        return floatval($usdtBalance);
    }

    public function availableMargin(): mixed
    {
        $data = $this->response['data'];

        $usdtBalance = $data['available'];

        return floatval($usdtBalance);
    }
}
