<?php

namespace App\Services\Exchange\Coinex\Responses;

use App\Services\Exchange\Repository\Coin;
use App\Services\Exchange\Repository\CoinCollection;
use App\Services\Exchange\Responses\CoinsResponseContract;
use Illuminate\Support\Str;

class CoinResponseAdapter extends BaseResponse implements CoinsResponseContract
{

    public function data(): ?CoinCollection
    {
        $data = $this->response['data'];

        $coinsArray = [];

        foreach ($data as $item) {

            $name = $item['base_ccy'];

            $coinsArray[] = Coin::create($name);
        }

        return CoinCollection::make($coinsArray)
            ->unique(function (Coin $coin) {
                return $coin->getName();
            });
    }
}
