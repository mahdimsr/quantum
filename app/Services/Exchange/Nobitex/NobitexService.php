<?php

namespace App\Services\Exchange\Nobitex;

use App\Services\Exchange\Nobitex\Responses\AllOrdersResponse;
use App\Services\Exchange\Nobitex\Responses\OrderResponse;
use App\Services\Exchange\Requests\OrderRequestContract;
use App\Services\Exchange\Responses\AllOrdersResponseContract;
use App\Services\Exchange\Responses\OrderResponseContract;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use Psr\Http\Message\ResponseInterface;

class NobitexService implements OrderRequestContract
{
    /**
     * @throws GuzzleException
     */
    public function orders(): AllOrdersResponseContract
    {
        $request = $this->request('GET', 'orderbook/all');

        return new AllOrdersResponse(json_decode($request->getBody()->getContents(), true));
    }

    public function order(string $coinName): OrderResponseContract
    {
        $request = $this->request('GET', "orderbook/$coinName");

        return new OrderResponse(json_decode($request->getBody()->getContents(), true));
    }

    /**
     * @throws GuzzleException
     */
    protected function request(string $method, string $path): ResponseInterface
    {
        $baseUri = Config::get('exchange.exchanges.nobitex.base_url');

        // add / from path: test/endpoint ==> /test/endpoint
        if (!Str::startsWith($path, '/')) {
            $path = "/$path";
        }

        $uri = $baseUri . $path;

        $client = new Client([
            'base_uri' => $path,
            'timeout' => 5,
        ]);

        $token = Config::get('exchange.exchanges.nobitex.auth_token');

        $options = [
            'headers' => [
                'Authorization' => 'Token ' . $token,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
        ];

        return $client->request(Str::upper($method), $uri, $options);
    }
}
