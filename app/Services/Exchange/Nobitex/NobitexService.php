<?php

namespace App\Services\Exchange\Nobitex;

use App\Services\Exchange\Enums\ExchangeResolutionEnum;
use App\Services\Exchange\Enums\OrderExecutionEnum;
use App\Services\Exchange\Enums\OrderTypeEnum;
use App\Services\Exchange\Nobitex\Responses\AllOrdersResponse;
use App\Services\Exchange\Nobitex\Responses\OHLCListResponse;
use App\Services\Exchange\Nobitex\Responses\GetOrderResponse;
use App\Services\Exchange\Nobitex\Responses\SetOrderResponse;
use App\Services\Exchange\Nobitex\Responses\StatsResponse;
use App\Services\Exchange\Nobitex\Responses\UserResponse;
use App\Services\Exchange\Requests\MarketStatsRequestContract;
use App\Services\Exchange\Requests\OHLCRequestContract;
use App\Services\Exchange\Requests\OrderRequestContract;
use App\Services\Exchange\Requests\UserRequestContract;
use App\Services\Exchange\Responses\AllOrdersResponseContract;
use App\Services\Exchange\Responses\OHLCListResponseContract;
use App\Services\Exchange\Responses\OHLCResponseContract;
use App\Services\Exchange\Responses\GetOrderResponseContract;
use App\Services\Exchange\Responses\SetOrderResponseContract;
use App\Services\Exchange\Responses\StatsResponseContract;
use App\Services\Exchange\Responses\UserResponseContract;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use Psr\Http\Message\ResponseInterface;

class NobitexService implements OrderRequestContract, MarketStatsRequestContract, OHLCRequestContract, UserRequestContract
{
    /**
     * @throws GuzzleException
     */
    public function orders(): AllOrdersResponseContract
    {
        $request = $this->request('GET', 'v2/orderbook/all');

        return new AllOrdersResponse(json_decode($request->getBody()->getContents(), true));
    }

    /**
     * @throws GuzzleException
     */
    public function order(string $coinName): GetOrderResponseContract
    {
        $request = $this->request('GET', "v2/orderbook/$coinName");

        return new GetOrderResponse(json_decode($request->getBody()->getContents(), true));
    }

    /**
     * @throws GuzzleException
     */
    public function marketStats(string $srcCurrency, string $dstCurrency): StatsResponseContract
    {
        $request = $this->request('GET', 'market/stats', ['srcCurrency' => $srcCurrency, 'dstCurrency' => $dstCurrency]);

        $response = json_decode($request->getBody()->getContents(), true);

        return new StatsResponse($response['stats']["$srcCurrency-$dstCurrency"]);
    }

    /**
     * @throws GuzzleException
     */
    public function ohlc(string $symbol, ExchangeResolutionEnum $resolutionEnum, int $to, int $from, int $countBack, int $page = 1): OHLCListResponseContract
    {
        $request = $this->request('GET', 'market/udf/history', [
            'symbol'     => $symbol,
            'resolution' => $resolutionEnum->value,
            'to'         => $to,
            'from'       => $from,
            'countback'  => $countBack,
            'page'       => $page
        ]);

        return new OHLCListResponse(json_decode($request->getBody()->getContents(), true));
    }

    /**
     * @throws GuzzleException
     */
    public function user(): UserResponseContract
    {
        $request = $this->request('GET', 'users/profile');

        return new UserResponse(json_decode($request->getBody()->getContents(), true));
    }

    /**
     * @throws GuzzleException
     */
    public function setOrder(
        OrderTypeEnum $orderBuyEnum,
        OrderExecutionEnum $orderExecutionEnum,
        string $srcCurrency,
        string $dstCurrency,
        string $amount,
        string $price,
        string $clientOrderId
    ): SetOrderResponseContract {
        $request = $this->request('POST', 'market/orders/add', null, [
            'type'          => $orderBuyEnum->value,
            'execution'     => $orderExecutionEnum->value,
            'srcCurrency'   => $srcCurrency,
            'dstCurrency'   => $dstCurrency,
            'amount'        => $amount,
            'price'         => $price,
            'clientOrderId' => $clientOrderId
        ]);

        return new SetOrderResponse(json_decode($request->getBody()->getContents(), true));
    }

    /**
     * @throws GuzzleException
     */
    protected function request(string $method, string $path, array|null $queryParams = null, array|null $bodyParams = null): ResponseInterface
    {
        $baseUri = Config::get('exchange.exchanges.nobitex.base_url');

        // add / from path: test/endpoint ==> /test/endpoint
        if (!Str::startsWith($path, '/')) {
            $path = "/$path";
        }

        $uri = $baseUri.$path;

        $client = new Client([
            'base_uri' => $path,
            'timeout'  => 5,
        ]);

        $token = Config::get('exchange.exchanges.nobitex.auth_token');

        $options = [
            'headers' => [
                'Authorization' => 'Token '.$token,
                'Accept'        => 'application/json',
                'Content-Type'  => 'application/json',
            ],
        ];

        if ($queryParams) {
            $options['query'] = $queryParams;
        }

        if ($bodyParams) {
            $options['json'] = $bodyParams;
        }

        return $client->request(Str::upper($method), $uri, $options);
    }
}
