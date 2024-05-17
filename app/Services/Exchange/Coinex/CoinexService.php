<?php

namespace App\Services\Exchange\Coinex;

use App\Services\Exchange\Coinex\Responses\CandleResponseAdapter;
use App\Services\Exchange\Coinex\Responses\OrderResponseAdapter;
use App\Services\Exchange\Enums\HttpMethodEnum;
use App\Services\Exchange\Requests\CandleRequestContract;
use App\Services\Exchange\Requests\OrderRequestContract;
use App\Services\Exchange\Requests\PositionRequestContract;
use App\Services\Exchange\Responses\AdjustPositionLeverageContract;
use App\Services\Exchange\Responses\CandleResponseContract;
use App\Services\Exchange\Responses\OrderResponseContract;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use Modules\CCXT\coinex;
use Psr\Http\Message\ResponseInterface;

class CoinexService implements CandleRequestContract, OrderRequestContract, PositionRequestContract
{
    private Client $client;
    private coinex $coinexClient;

    public function __construct()
    {
        $baseUri            = Config::get('exchange.exchanges.coinex.base_url') . '/v2/futures';
        $this->coinexClient = new coinex([
                                             'apiKey' => Config::get('exchange.exchanges.coinex.access_id'),
                                             'secret' => Config::get('exchange.exchanges.coinex.secret_key'),
                                         ]);

        $this->client = new Client([
                                       'uri'     => $baseUri,
                                       'headers' => [
                                           'accept' => 'application/json',
                                       ],
                                   ]);
    }

    /**
     * @throws GuzzleException
     */
    private function getAuthorizationClient(HttpMethodEnum $httpMethodEnum, string $endpoint, ?array $queryString = null, ?array $body = null): ResponseInterface
    {
        $baseUri     = Config::get('exchange.exchanges.coinex.base_url');
        $requestPath = "/v2/futures/$endpoint";
        $fullUri     = $baseUri . $requestPath;
        $timestamp   = $this->time();

        $authorizationToken = $this->generateAuthorizationToken($httpMethodEnum->value, $requestPath, $timestamp, $queryString, $body);

        $client = new Client([
                                 'headers' => [
                                     'accept'             => 'application/json',
                                     'X-COINEX-KEY'       => Config::get('exchange.exchanges.coinex.access_id'),
                                     'X-COINEX-SIGN'      => $authorizationToken,
                                     'X-COINEX-TIMESTAMP' => $timestamp,
                                 ],
                             ]);

        $options = [];

        return $client->request($httpMethodEnum->value, $fullUri, $options);
    }

    private function generateAuthorizationToken(string $httpMethod, string $requestPath, string $timestamp, ?array $queryParams = null, ?array $bodyParam = null): string
    {
        $preparedToken = Str::upper($httpMethod);

        $queryParamsStringify = '';

        if ($queryParams) {

            foreach ($queryParams as $key => $value) {

                $queryParamsStringify .= "$key=$value";
            }

            $requestPath = $requestPath . '?' . $queryParamsStringify;
        }

        $preparedToken .= $requestPath;

        if ($bodyParam) {

            $bodyStringify = json_encode($bodyParam);

            $preparedToken .= $bodyStringify;
        }

        $preparedToken .= $timestamp;

        $secretKey = Config::get('exchange.exchanges.coinex.secret_key');


        return hash_hmac('sha256', $preparedToken, $secretKey);
    }

    public function time(): mixed
    {
        $uri = Config::get('exchange.exchanges.coinex.base_url') . '/v2/time';

        $client = new Client([
                                 'headers' => [
                                     'accept' => 'application/json',
                                 ],
                             ]);

        try {

            $response = $client->get($uri);

            $jsonResponse = json_decode($response->getBody()->getContents(), true);

            return $jsonResponse['data']['timestamp'];

        } catch (GuzzleException $e) {

            dd($e);
        }
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


    public function orders(string $marketType): ?OrderResponseContract
    {
        try {


            return new OrderResponseAdapter($this->coinexClient->v2_private_get_futures_finished_order(['market_type' => Str::upper($marketType)]));

        } catch (\Exception $e) {

            logs()->critical($e);

            return null;
        }
    }

    public function placeOrder(string $symbol, string $marketType, string $side, string $type, int $amount, int $price): mixed
    {
        try {

            dd($this->coinexClient->v2_private_post_futures_order([
                                                                      'market'      => $symbol,
                                                                      'market_type' => Str::upper($marketType),
                                                                      'side'        => $side,
                                                                      'type'        => $type,
                                                                      'amount'      => $amount,
                                                                      'price'       => $price,
                                                                  ]));

        } catch (\Exception $e) {

            logs()->critical($e);

            return null;
        }
    }

    public function adjustPositionLeverage(string $symbol, string $marketType, string $marginMode, int $leverage): ?AdjustPositionLeverageContract
    {
        try {

            dd($this->coinexClient->v2_private_post_futures_adjust_position_leverage(
                [
                    'market'      => $symbol,
                    'market_type' => Str::upper($marketType),
                    'margin_mode' => $marginMode,
                    'leverage'    => $leverage,
                ]));

        } catch (\Exception $e) {

            logs()->critical($e);

            return null;
        }
    }
}
