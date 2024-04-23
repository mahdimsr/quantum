<?php

namespace App\Services\Exchange\Coinex;

use App\Services\Exchange\Coinex\Responses\CandleResponseAdapter;
use App\Services\Exchange\Requests\CandleRequestContract;
use App\Services\Exchange\Responses\CandleResponseContract;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Config;

class CoinexService implements CandleRequestContract
{
    protected Client $client;

    public function __construct()
    {

        $futuresBaseUrl = Config::get('exchange.exchanges.coinex.base_url').'/futures/';

        $this->client = new Client([
            'base_uri' => $futuresBaseUrl,
            'headers'  => [
                'accept' => 'application/json'
            ],
        ]);
    }


    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function candles(string $symbol, string $period, string $limit = null): CandleResponseContract
    {
        $request = $this->client->get('kline', [
            'query' => [
                'market' => $symbol,
                'period' => $period,
                'limit'  => $limit,
            ]
        ]);

        return new CandleResponseAdapter(json_decode($request->getBody()->getContents(), true));
    }
}
