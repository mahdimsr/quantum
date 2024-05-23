<?php

namespace App\Services\Exchange\Coinex\Responses;

use App\Services\Exchange\Repository\Asset;
use App\Services\Exchange\Repository\AssetCollection;
use App\Services\Exchange\Responses\AssetBalanceContract;

class FuturesAssetResponse implements AssetBalanceContract
{
    protected array $data;

    public function __construct(array $data) {

        $this->data = $data['data'];
    }

    public function data(): AssetCollection
    {
        $assetArray = collect($this->data)->map(fn($item) => Asset::fromArray($item))->toArray();

        return AssetCollection::make($assetArray);
    }
}
