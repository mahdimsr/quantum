<?php

namespace App\Services\Exchange\Nobitex\Responses;

use App\Services\Exchange\Responses\OHLCListResponseContract;
use App\Services\Exchange\Responses\OHLCResponseContract;

class OHLCListResponse implements OHLCListResponseContract
{
    protected array $response;

    public function __construct(array $response) {

        $this->response = $response;
    }

    public function status(): string
    {
        return $this->response['s'];
    }

    public function ohlc(mixed $index): OHLCResponseContract
    {

    }

    public function error(): string
    {
        return $this->response['errmsg'];
    }
}
