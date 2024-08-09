<?php

namespace App\Services\Exchange\Bingx;

use App\Services\Exchange\Bingx\Response\CandleResponseAdapter;
use App\Services\Exchange\Requests\CandleRequestContract;
use App\Services\Exchange\Responses\CandleResponseContract;
use Modules\CCXT\bingx;
use Illuminate\Support\Facades\Config;

class BingXService implements CandleRequestContract
{
    private bingx $bingxClient;

    public function __construct()
    {
        $this->bingxClient = new bingx([
            'apiKey' => Config::get('exchange.exchanges.bingx.api_key'),
            'secret' => Config::get('exchange.exchanges.bingx.secret_key'),
        ]);
    }

    public function candles(string $symbol, string $limit, string $period): CandleResponseContract
    {
        $data = $this->bingxClient->swap_v1_private_get_market_markpriceklines([
            'symbol' => $symbol,
            'limit' => $limit,
            'interval' => $period
        ]);

        return new CandleResponseAdapter($data);
    }
}
