<?php

namespace App\Services\Exchange\Coinex\Responses;

use App\Services\Exchange\Responses\OHLCListResponseContract;
use App\Services\Exchange\Responses\OHLCResponseContract;
use Illuminate\Support\Collection;

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

    public function all(): Collection
    {
        $candles = [];

        for ($i = 0; $i < $this->count(); $i++) {
            $candle = $this->ohlc($i);

            $candles[] = $candle;
        }

        return collect($candles);
    }
}
