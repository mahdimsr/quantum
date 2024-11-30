<?php

namespace App\Services\Exchange\Coinex\Responses;

use App\Services\Exchange\Responses\AssetBalanceContract;

class AssetBalanceResponseAdapter extends BaseResponse implements AssetBalanceContract
{

    public function balance(): mixed
    {
        $usdt = collect($this->response['data'])->filter(fn($item) => $item['ccy'] == 'USDT')->first();

        return $usdt['available'];
    }

    public function availableMargin(): mixed
    {
        $usdt = collect($this->response['data'])->filter(fn($item) => $item['ccy'] == 'USDT')->first();

        return $usdt['available'];
    }
}
