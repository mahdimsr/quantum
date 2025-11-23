<?php

namespace App\Services\Exchange\Bitunix\Responses;

use App\Services\Exchange\Repository\Coin;
use App\Services\Exchange\Repository\CoinCollection;
use App\Services\Exchange\Responses\CoinsResponseContract;

class CoinResponseAdapter extends BaseResponse implements CoinsResponseContract
{

    public function data(): ?CoinCollection
    {
        $data = $this->response['data'];

        $coinsArray = [];

        foreach ($data as $item) {

            $name = $item['base'];

            $coinsArray[] = Coin::create($name);
        }

        return CoinCollection::make($coinsArray)
            ->unique(function (Coin $coin) {
                return $coin->getName();
            });
    }
}
