<?php

namespace App\Services\Exchange\Bitunix;

use App\Services\Exchange\Bitunix\Response\CandleResponseAdapter;
use App\Services\Exchange\Requests\CandleRequestContract;
use App\Services\Exchange\Responses\CandleResponseContract;
use Msr\LaravelBitunixApi\Facades\LaravelBitunixApi;

class BitunixService implements CandleRequestContract
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
}
