<?php

namespace App\Services\Exchange\BingX\Response;

use App\Services\Exchange\Repository\Coin;
use App\Services\Exchange\Repository\CoinCollection;
use App\Services\Exchange\Responses\CoinsResponseContract;

class CoinResponseAdapter implements CoinsResponseContract
{
    protected array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function data(): ?CoinCollection
    {
        $mainData = $this->data['data'];

        $coinsArray = [];

        foreach ($mainData as $item) {

            $symbol = $item['symbol'];

            $name = explode('-', $symbol)[0];

            $coinsArray[] = Coin::create($name);
        }

        return CoinCollection::make($coinsArray)
            ->unique(function (Coin $coin) {
                return $coin->getName();
            });
    }
}
