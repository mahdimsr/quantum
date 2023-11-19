<?php

namespace App\Services\Exchange\Requests;

use App\Services\Exchange\Enums\ExchangeResolutionEnum;
use App\Services\Exchange\Responses\OHLCResponseContract;

interface OHLCRequestContract
{
    public function ohlc(string $symbol, ExchangeResolutionEnum $resolutionEnum, int $to,int $from, int $countBack, int $page = 1): OHLCResponseContract;
}
