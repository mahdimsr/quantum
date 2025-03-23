<?php

namespace App\Services\Exchange\BingX\Response;

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

        if ($balanceArray) {

            return $balanceArray['balance'];
        }

        return null;
    }

    public function availableMargin(): mixed
    {
        $balanceArray =  $this->data['data']['balance'];

        if ($balanceArray) {

            return $balanceArray['availableMargin'];
        }

        return null;
    }
}
