<?php

namespace App\Services\Exchange\Bingx;

use App\Services\Exchange\Bingx\Response\CandleResponseAdapter;
use App\Services\Exchange\Bingx\Response\CoinResponseAdapter;
use App\Services\Exchange\BingX\Response\SetLeverageResponseAdapter;
use App\Services\Exchange\Requests\CandleRequestContract;
use App\Services\Exchange\Requests\CoinsRequestContract;
use App\Services\Exchange\Requests\SetLeverageRequestContract;
use App\Services\Exchange\Responses\AssetBalanceContract;
use App\Services\Exchange\Responses\CandleResponseContract;
use App\Services\Exchange\Responses\CoinsResponseContract;
use App\Services\Exchange\Responses\SetLeverageResponseContract;
use Illuminate\Support\Str;
use Modules\CCXT\bingx;
use Illuminate\Support\Facades\Config;

class BingXService implements CandleRequestContract, CoinsRequestContract, SetLeverageRequestContract
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

    public function coins(): CoinsResponseContract
    {
        $data = $this->bingxClient->swap_v2_public_get_quote_contracts();

        return new CoinResponseAdapter($data);
    }

    public function setLeverage(string $symbol, string $side, string $leverage): SetLeverageResponseContract
    {
        $data = $this->bingxClient->swap_v2_private_post_trade_leverage([
            'symbol' => $symbol,
            'side' => Str::of($side)->upper()->toString(),
            'leverage' => $leverage,
            'timestamp' => now()->timestamp
        ]);

        return new SetLeverageResponseAdapter($data);
    }
}
