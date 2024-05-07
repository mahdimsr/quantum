<?php

namespace App\Services\Exchange\Facade;

use App\Services\Exchange\Coinex\CoinexService;
use App\Services\Exchange\Enums\ExchangeResolutionEnum;
use App\Services\Exchange\Enums\OrderExecutionEnum;
use App\Services\Exchange\Enums\OrderTypeEnum;
use App\Services\Exchange\Responses\AllOrdersResponseContract;
use App\Services\Exchange\Responses\CandleResponseContract;
use App\Services\Exchange\Responses\OHLCListResponseContract;
use App\Services\Exchange\Responses\OHLCResponseContract;
use App\Services\Exchange\Responses\GetOrderResponseContract;
use App\Services\Exchange\Responses\SetOrderResponseContract;
use App\Services\Exchange\Responses\StatsResponseContract;
use App\Services\Exchange\Responses\UserResponseContract;
use Illuminate\Support\Facades\Facade;

/**
 * @method static CandleResponseContract market(string $symbol, string $period, string $limit = null)
 * @method static mixed orders(string $marketType)
 */
class Exchange extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return CoinexService::class;
    }
}
