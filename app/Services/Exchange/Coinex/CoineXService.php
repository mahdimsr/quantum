<?php

namespace App\Services\Exchange\Coinex;

use App\Services\Exchange\Coinex\Responses\AssetBalanceResponseAdapter;
use App\Services\Exchange\Coinex\Responses\CandleResponseAdapter;
use App\Services\Exchange\Coinex\Responses\ClosePositionResponseAdapter;
use App\Services\Exchange\Coinex\Responses\CoinResponseAdapter;
use App\Services\Exchange\Coinex\Responses\OrderResponseAdapter;
use App\Services\Exchange\Coinex\Responses\PositionResponseAdapter;
use App\Services\Exchange\Coinex\Responses\SetLeverageResponseAdapter;
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
use App\Services\Exchange\Responses\SetLeverageResponseContract;
use App\Services\Exchange\Responses\SetOrderResponseContract;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use Modules\CCXT\coinex;

class CoineXService implements CandleRequestContract, AssetRequestContract, CoinsRequestContract, SetLeverageRequestContract, OrderRequestContract, PositionRequestContract
{
    private Client $client;
    private coinex $coinexClient;

    public function __construct()
    {
        $baseUri = Config::get('exchange.exchanges.coinex.base_url') . '/v2/futures/';
        $this->coinexClient = new coinex([
            'apiKey' => Config::get('exchange.exchanges.coinex.access_id'),
            'secret' => Config::get('exchange.exchanges.coinex.secret_key'),
        ]);

        $this->client = new Client([
            'base_uri' => $baseUri,
            'headers' => [
                'accept' => 'application/json',
            ],
        ]);
    }


    public function candles(string $symbol, string $period, string $limit = null): CandleResponseContract
    {
        $params = [
            'market' => $symbol,
            'period' => $period,
        ];

        if ($limit) {

            $params = array_merge($params, ['limit' => $limit]);
        }

        $data = $this->coinexClient->v2_public_get_futures_kline($params);


        return new CandleResponseAdapter($data);
    }

    public function futuresBalance(): ?AssetBalanceContract
    {
        $data = $this->coinexClient->v2_private_get_assets_futures_balance();

        return new AssetBalanceResponseAdapter($data);
    }

    public function coins(): CoinsResponseContract
    {
        $data = $this->coinexClient->v2_public_get_futures_market();

        return new CoinResponseAdapter($data);
    }

    public function setLeverage(string $symbol, SideEnum $side, string $leverage): SetLeverageResponseContract
    {
        $data = $this->coinexClient->v2_private_post_futures_adjust_position_leverage([
            'market' => $symbol,
            'market_type' => 'FUTURES',
            'margin_mode' => 'isolated',
            'leverage' => intval($leverage),
        ]);

        return new SetLeverageResponseAdapter($data);
    }

    public function orders(?string $symbol = null): OrderListResponseContract
    {
        // TODO: Implement orders() method.
    }

    public function setOrder(string $symbol, TypeEnum $typeEnum, SideEnum $sideEnum, SideEnum $positionSide, float $amount, float $price, mixed $client_id = null, ?Target $takeProfit = null, ?Target $stopLoss = null): ?SetOrderResponseContract
    {
        $side = Str::of($sideEnum->convertToBuySell())->lower()->toString();
        $type = Str::of($typeEnum->value)->lower()->toString();

        $params = [
            'market' => $symbol,
            'market_type' => 'FUTURES',
            'side' => $side,
            'type' => $type,
            'amount' => $amount,
            'client_id' => $client_id,
        ];

        if (Str::of(TypeEnum::LIMIT->value)->lower()->is($type)) {

            $params = array_merge($params, [
                'price' => $price,
            ]);
        }

        if ($sideEnum == 'sell') {

            $params = array_merge($params, [
                'price' => $price,
            ]);
        }

        $data = $this->coinexClient->v2_private_post_futures_order($params);

        return new OrderResponseAdapter($data);
    }

    public function currentPosition(string $symbol): ?PositionResponseContract
    {
        $data = $this->coinexClient->v2_private_get_futures_pending_position([
            'market' => $symbol,
            'market_type' => 'FUTURES',
        ]);

        return new PositionResponseAdapter($data);
    }

    public function closePositionByPositionId(string $positionId, ?string $symbol = null): ?ClosePositionResponseContract
    {
        $data = $this->coinexClient->v2_private_post_futures_close_position([
            'market' => $symbol,
            'market_type' => 'FUTURES',
            'type' => 'market',
            'client_id' => $positionId
        ]);

        return new ClosePositionResponseAdapter($data);
    }

    public function setStopLoss(string $symbol, mixed $stopLossPrice, string $stopLossType): ?PositionResponseContract
    {
        $data = $this->coinexClient->v2_private_post_futures_set_position_stop_loss([
            'market' => $symbol,
            'market_type' => 'FUTURES',
            'stop_loss_type' => 'mark_price',
            'stop_loss_price' => $stopLossPrice
        ]);

        return new PositionResponseAdapter($data);
    }

    public function setTakeProfit(string $symbol, mixed $takeProfitPrice, string $takeProfitType): ?PositionResponseContract
    {
        $data = $this->coinexClient->v2_private_post_futures_set_position_take_profit([
            'market' => $symbol,
            'market_type' => 'FUTURES',
            'take_profit_type' => 'mark_price',
            'take_profit_price' => $takeProfitPrice
        ]);

        return new PositionResponseAdapter($data);
    }
}
