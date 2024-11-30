<?php

namespace App\Services\Exchange\Coinex;

use App\Services\Exchange\Coinex\Responses\AssetBalanceResponseAdapter;
use App\Services\Exchange\Coinex\Responses\CandleResponseAdapter;
use App\Services\Exchange\Requests\AssetRequestContract;
use App\Services\Exchange\Requests\CandleRequestContract;
use App\Services\Exchange\Responses\AssetBalanceContract;
use App\Services\Exchange\Responses\CandleResponseContract;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Config;
use Modules\CCXT\coinex;

class CoineXService implements CandleRequestContract, AssetRequestContract
{
    private Client $client;
    private coinex $coinexClient;

    public function __construct()
    {
        $baseUri = Config::get('exchange.exchanges.coinex.base_url') . '/v2/futures/';
        $this->coinexClient = new coinex([
            'apiKey' => Config::get('exchange.exchanges.coinex.access_id'),
            'secret' => Config::get('exchange.exchanges.coinex.secret_key'),
        ]);

        $this->client = new Client([
            'base_uri' => $baseUri,
            'headers' => [
                'accept' => 'application/json',
            ],
        ]);
    }


    public function candles(string $symbol, string $period, string $limit = null): CandleResponseContract
    {
        $params = [
            'market' => $symbol,
            'period' => $period,
        ];

        if ($limit) {

            $params = array_merge($params, ['limit' => $limit]);
        }

        $data = $this->coinexClient->v2_public_get_futures_kline($params);


        return new CandleResponseAdapter($data);
    }

    public function futuresBalance(): ?AssetBalanceContract
    {
        $data = $this->coinexClient->v2_private_get_assets_futures_balance();

        return new AssetBalanceResponseAdapter($data);
    }
}
