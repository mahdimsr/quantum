<?php

namespace App\Services\Exchange\Requests;

use App\Services\Exchange\Responses\StatsResponseContract;

interface MarketStatsRequestContract
{
    public function marketStats(string $srcCurrency, string $dstCurrency): StatsResponseContract;
}
