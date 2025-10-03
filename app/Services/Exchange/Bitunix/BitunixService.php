<?php

namespace App\Services\Exchange\Bitunix;

use App\Services\Exchange\Bitunix\Responses\AssetBalanceResponseAdapter;
use App\Services\Exchange\Bitunix\Responses\CandleResponseAdapter;
use App\Services\Exchange\Requests\AssetRequestContract;
use App\Services\Exchange\Requests\CandleRequestContract;
use App\Services\Exchange\Responses\AssetBalanceContract;
use App\Services\Exchange\Responses\CandleResponseContract;
use Msr\LaravelBitunixApi\Facades\LaravelBitunixApi;

class BitunixService implements CandleRequestContract, AssetRequestContract
{

    /**
     * @throws \Throwable
     */
    public function candles(string $symbol, string $period, string $limit = null): CandleResponseContract
    {
        $limit = $limit ?: 100;
        $response = LaravelBitunixApi::getFutureKline($symbol, $period, $limit);

        throw_if($response->getStatusCode() != 200 ,'No success response');

        $data = json_decode($response->getBody()->getContents(), true);

        return new CandleResponseAdapter($data);
    }

    /**
     * @throws \Throwable
     */
    public function futuresBalance(): ?AssetBalanceContract
    {
        $response = LaravelBitunixApi::getSingleAccount('USDT');

        throw_if($response->getStatusCode() != 200, 'No success response');

        $data = json_decode($response->getBody()->getContents(), true);

        return new AssetBalanceResponseAdapter($data);
    }
}
