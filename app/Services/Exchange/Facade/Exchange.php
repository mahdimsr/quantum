<?php

namespace App\Services\Exchange\Facade;

use App\Services\Exchange\Responses\AllOrdersResponseContract;
use App\Services\Exchange\Responses\OrderResponseContract;
use Illuminate\Support\Facades\Facade;

/**
 * @method static AllOrdersResponseContract orders()
 * @method static OrderResponseContract order(string $coinName)
 */
class Exchange extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'ExchangeService';
    }
}
