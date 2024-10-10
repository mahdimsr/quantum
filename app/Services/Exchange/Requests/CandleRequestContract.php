<?php

namespace App\Services\Exchange\Requests;

use App\Services\Exchange\Responses\CandleResponseContract;

interface CandleRequestContract
{
    public function candles(string $symbol, string $period, string $limit = null): CandleResponseContract;
}
