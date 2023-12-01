<?php

namespace App\Services\Exchange\Nobitex\Responses;

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
        return $this->response['s'];
    }

    public function ohlc(mixed $index): OHLCResponseContract
    {
        $high = $this->response['h'][$index];
        $close = $this->response['c'][$index];
        $open = $this->response['o'][$index];
        $low = $this->response['l'][$index];
        $volume = $this->response['v'][$index];
        $time = $this->response['t'][$index];

        return new OHLCResponse([
            'high' => $high,
            'close' => $close,
            'open' => $open,
            'low' => $low,
            'volume' => $volume,
            'time' => $time,
        ]);
    }

    public function error(): string
    {
        return $this->response['errmsg'];
    }

    public function count(): int
    {
        return count($this->response['t']);
    }
}
