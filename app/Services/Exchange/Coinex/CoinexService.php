<?php

namespace App\Services\Exchange\Coinex;

use App\Services\Exchange\Coinex\Responses\OHLCListResponse;
use App\Services\Exchange\Enums\ExchangeResolutionEnum;
use App\Services\Exchange\Requests\OHLCRequestContract;
use App\Services\Exchange\Responses\OHLCListResponseContract;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class CoinexService implements OHLCRequestContract
{

    /**
     * @throws GuzzleException
     */
    public function ohlc(string $symbol, ExchangeResolutionEnum $resolutionEnum, int $to, int $from, int $countBack, int $page = 1): OHLCListResponseContract
    {
        // https://www.coinex.com/res/contract/market/kline?market=LRCUSDT&start_time=1702900800&end_time=1702906285&interval=3600


        $client = new Client();


        $request = $client->get('https://www.coinex.com/res/contract/market/kline', [
            'query' => [
                'market'     => $symbol,
                'start_time' => $from,
                'end_time'   => $to,
                'interval'   => $resolutionEnum->value,
            ]
        ]);


        return new OHLCListResponse(json_decode($request->getBody()->getContents(), true));
    }
}
