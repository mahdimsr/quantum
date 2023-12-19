<?php

namespace App\Services\Exchange\Coinex\Responses;

use App\Services\Exchange\Responses\OHLCListResponseContract;
use App\Services\Exchange\Responses\OHLCResponseContract;

class OHLCListResponse implements OHLCListResponseContract
{
    protected array $response;

    public function __construct(array $response)
    {

        $this->response = $response;
    }

    public function status(): string
    {
        // TODO: Implement status() method.
    }

    public function ohlc(mixed $index): OHLCResponseContract
    {
        $candleData = $this->response['data'][$index];

        dd($candleData);

        return new OHLCResponse($candleData);
    }

    public function count(): int
    {
        return count($this->response['data']);
    }

    public function error(): string
    {
        return 'Something Wrong,...';
    }
}
