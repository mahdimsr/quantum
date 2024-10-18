<?php

namespace App\Services\Exchange\BingX;

use App\Services\Exchange\BingX\Response\AssetResponseAdapter;
use App\Services\Exchange\BingX\Response\CandleResponseAdapter;
use App\Services\Exchange\BingX\Response\ClosePositionResponseAdapter;
use App\Services\Exchange\BingX\Response\CoinResponseAdapter;
use App\Services\Exchange\BingX\Response\PositionResponseAdapter;
use App\Services\Exchange\BingX\Response\SetLeverageResponseAdapter;
use App\Services\Exchange\BingX\Response\SetOrderResponseAdapter;
use App\Services\Exchange\Enums\SideEnum;
use App\Services\Exchange\Enums\TypeEnum;
use App\Services\Exchange\Repository\Target;
use App\Services\Exchange\Requests\AssetRequestContract;
use App\Services\Exchange\Requests\CandleRequestContract;
use App\Services\Exchange\Requests\CoinsRequestContract;
use App\Services\Exchange\Requests\OrderRequestContract;
use App\Services\Exchange\Requests\PositionRequestContract;
use App\Services\Exchange\Requests\SetLeverageRequestContract;
use App\Services\Exchange\Responses\AssetBalanceContract;
use App\Services\Exchange\Responses\CandleResponseContract;
use App\Services\Exchange\Responses\ClosePositionResponseContract;
use App\Services\Exchange\Responses\CoinsResponseContract;
use App\Services\Exchange\Responses\OrderListResponseContract;
use App\Services\Exchange\Responses\PositionResponseContract;
use App\Services\Exchange\Responses\SetOrderResponseContract;
use App\Services\Exchange\Responses\SetLeverageResponseContract;
use Illuminate\Support\Str;
use Modules\CCXT\bingx;
use Illuminate\Support\Facades\Config;

class BingXService implements CandleRequestContract, CoinsRequestContract, SetLeverageRequestContract, OrderRequestContract, AssetRequestContract, PositionRequestContract
{
    private bingx $bingxClient;

    public function __construct()
    {
        $this->bingxClient = new bingx([
            'apiKey' => Config::get('exchange.exchanges.bingx.api_key'),
            'secret' => Config::get('exchange.exchanges.bingx.secret_key'),
        ]);
    }

    public function candles(string $symbol, string $period, string $limit = null): CandleResponseContract
    {
        try {

            $data = $this->bingxClient->swap_v3_public_get_quote_klines([
                'symbol' => $symbol,
                'limit' => $limit,
                'interval' => $period
            ]);

        } catch (\Exception $exception) {

            $data = [
                'code' => $exception->getCode(),
                'msg' => $exception->getMessage(),
                'data' => [],
            ];
        }

        return new CandleResponseAdapter($data);
    }

    public function coins(): CoinsResponseContract
    {
        $data = $this->bingxClient->swap_v2_public_get_quote_contracts();

        return new CoinResponseAdapter($data);
    }

    public function setLeverage(string $symbol, SideEnum $side, string $leverage): SetLeverageResponseContract
    {
        $data = $this->bingxClient->swap_v2_private_post_trade_leverage([
            'symbol' => $symbol,
            'side' => Str::of($side->convertToLongShort())->upper()->toString(),
            'leverage' => $leverage,
            'timestamp' => now()->timestamp
        ]);

        return new SetLeverageResponseAdapter($data);
    }

    public function setOrder(string $symbol, TypeEnum $typeEnum, SideEnum $sideEnum, SideEnum $positionSide, float $amount, float $price, mixed $client_id = null, ?Target $takeProfit = null, ?Target $stopLoss = null): ?SetOrderResponseContract
    {
        $params = [
            'symbol' => $symbol,
            'type' => Str::of($typeEnum->value)->upper()->toString(),
            'side' => Str::of($sideEnum->convertToBuySell())->upper()->toString(),
            'positionSide' => Str::of($positionSide->convertToLongShort())->upper()->toString(),
            'quantity' => $amount,
            'price' => $price,
            'timestamp' => now()->timestamp,
        ];

        if ($client_id) {
            $params['clientOrderId'] = $client_id;
        }

        if ($takeProfit) {
            $params['takeProfit'] = json_encode($takeProfit->toArray());
        }

        if ($stopLoss) {
            $params['stopLoss'] = json_encode($stopLoss->toArray());
        }

        $data = $this->bingxClient->swap_v2_private_post_trade_order($params);

        if (is_string($data)) {

            $data = json_decode($data, true);
        }

        return new SetOrderResponseAdapter($data);
    }

    public function futuresBalance(): ?AssetBalanceContract
    {
        $data = $this->bingxClient->swap_v2_private_get_user_balance([
            'timestamp' => now()->timestamp
        ]);

        return new AssetResponseAdapter($data);
    }

    public function orders(?string $symbol = null): OrderListResponseContract
    {
        $params = [];

        if ($symbol) {

            $params = array_merge($params, [
                'symbol' => $symbol
            ]);
        }


        $data = $this->bingxClient->swap_v2_private_get_user_positions($params);

        dd($data);
    }

    public function currentPosition(string $symbol): ?PositionResponseContract
    {
        $data = $this->bingxClient->swap_v2_private_get_user_positions([
            'symbol' => $symbol
        ]);

        return new PositionResponseAdapter($data);
    }

    public function closePositionByPositionId(string $positionId): ?ClosePositionResponseContract
    {
        $data = $this->bingxClient->swap_v1_private_post_trade_closeposition([
            'positionId' => $positionId,
            'timestamp' => now()->timestamp
        ]);

        return new ClosePositionResponseAdapter($data);
    }
}
