<?php

namespace App\Services\Exchange\Bingx\Response;

use App\Services\Exchange\Responses\AssetBalanceContract;

class AssetResponseAdapter extends BingXResponse implements AssetBalanceContract
{
    protected array $data;

    public function __construct(array $data) {

        $this->data = $data;
    }

    public function balance(): mixed
    {
        $balanceArray =  $this->data['data']['balance'];

//        $usdtBalance = collect($balanceArray)->filter(fn($item) => $item['asset'] == 'USDT')->first();

        if ($balanceArray) {

            return $balanceArray['balance'];
        }

        return null;
    }
}
