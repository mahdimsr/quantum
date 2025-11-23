<?php

namespace App\Services\Exchange\Bitunix;

use App\Services\Exchange\Bitunix\Responses\AssetBalanceResponseAdapter;
use App\Services\Exchange\Bitunix\Responses\CandleResponseAdapter;
use App\Services\Exchange\Bitunix\Responses\ClosePositionResponseAdapter;
use App\Services\Exchange\Bitunix\Responses\OrderListResponseAdapter;
use App\Services\Exchange\Bitunix\Responses\OrderResponseAdapter;
use App\Services\Exchange\Bitunix\Responses\PositionHistoryResponseAdapter;
use App\Services\Exchange\Bitunix\Responses\PositionResponseAdapter;
use App\Services\Exchange\Bitunix\Responses\SetLeverageResponseAdapter;
use App\Services\Exchange\Contracts\TimeframeContract;
use App\Services\Exchange\Enums\SideEnum;
use App\Services\Exchange\Enums\TimeframeEnum;
use App\Services\Exchange\Enums\TypeEnum;
use App\Services\Exchange\Repository\Target;
use App\Services\Exchange\Requests\AssetRequestContract;
use App\Services\Exchange\Requests\CandleRequestContract;
use App\Services\Exchange\Requests\OrderRequestContract;
use App\Services\Exchange\Requests\PositionRequestContract;
use App\Services\Exchange\Requests\SetLeverageRequestContract;
use App\Services\Exchange\Responses\AssetBalanceContract;
use App\Services\Exchange\Responses\CandleResponseContract;
use App\Services\Exchange\Responses\ClosePositionResponseContract;
use App\Services\Exchange\Responses\OrderListResponseContract;
use App\Services\Exchange\Responses\PositionResponseContract;
use App\Services\Exchange\Responses\SetLeverageResponseContract;
use App\Services\Exchange\Responses\SetOrderResponseContract;
use Illuminate\Support\Str;
use Msr\LaravelBitunixApi\Facades\LaravelBitunixApi;

class BitunixService implements CandleRequestContract, AssetRequestContract, SetLeverageRequestContract, PositionRequestContract, OrderRequestContract, TimeframeContract
{

    /**
     * @throws \Throwable
     */
    public function candles(string $symbol, string $period, string $limit = null): CandleResponseContract
    {
        $limit = $limit ?: 100;
        $response = LaravelBitunixApi::getFutureKline($symbol, $period, $limit);

        throw_if($response->getStatusCode() != 200 ,'No success response');

        $data = json_decode($response->getBody()->getContents(), true);

        return new CandleResponseAdapter($data);
    }

    /**
     * @throws \Throwable
     */
    public function futuresBalance(): ?AssetBalanceContract
    {
        $response = LaravelBitunixApi::getSingleAccount('USDT');

        throw_if($response->getStatusCode() != 200, 'No success response');

        $data = json_decode($response->getBody()->getContents(), true);

        return new AssetBalanceResponseAdapter($data);
    }

    /**
     * @throws \Throwable
     */
    public function setLeverage(string $symbol, SideEnum $side, string $leverage): SetLeverageResponseContract
    {
        $response = LaravelBitunixApi::changeLeverage($symbol, 'USDT', intval($leverage));

        throw_if($response->getStatusCode() != 200, 'No success response');

        $data = json_decode($response->getBody()->getContents(), true);

        return new SetLeverageResponseAdapter($data);
    }

    /**
     * @throws \Throwable
     */
    public function currentPosition(string $symbol): ?PositionResponseContract
    {
        $response = LaravelBitunixApi::getPendingPositions($symbol);

        throw_if($response->getStatusCode() != 200, 'No success response');

        $data = json_decode($response->getBody()->getContents(), true);

        return new PositionResponseAdapter($data);
    }

    /**
     * @throws \Throwable
     */
    public function closePositionByPositionId(string $positionId, ?string $symbol = null): ?ClosePositionResponseContract
    {
        $response = LaravelBitunixApi::flashClosePosition($positionId);

        throw_if($response->getStatusCode() != 200, 'No success response');

        $data = json_decode($response->getBody()->getContents(), true);

        return new ClosePositionResponseAdapter($data);
    }

    /**
     * @throws \Throwable
     */
    public function setStopLoss(string $symbol, mixed $stopLossPrice, string $stopLossType): ?PositionResponseContract
    {
        // For Bitunix, we need to get the position first to get the positionId
        $positionResponse = $this->currentPosition($symbol);
        if (!$positionResponse || !$positionResponse->position()) {
            return null;
        }

        $positionId = $positionResponse->position()->getPositionId();

        $response = LaravelBitunixApi::placePositionTpSlOrder(
            $symbol,
            $positionId,
            null, // tpPrice
            null, // tpStopType
            (string) $stopLossPrice, // slPrice
            'MARK_PRICE' // slStopType
        );

        throw_if($response->getStatusCode() != 200, 'No success response');

        $data = json_decode($response->getBody()->getContents(), true);

        return new PositionResponseAdapter($data);
    }

    /**
     * @throws \Throwable
     */
    public function setTakeProfit(string $symbol, mixed $takeProfitPrice, string $takeProfitType): ?PositionResponseContract
    {
        // For Bitunix, we need to get the position first to get the positionId
        $positionResponse = $this->currentPosition($symbol);
        if (!$positionResponse || !$positionResponse->position()) {
            return null;
        }

        $positionId = $positionResponse->position()->getPositionId();

        $response = LaravelBitunixApi::placePositionTpSlOrder(
            $symbol,
            $positionId,
            (string) $takeProfitPrice, // tpPrice
            'LAST_PRICE', // tpStopType
            null, // slPrice
            null  // slStopType
        );

        throw_if($response->getStatusCode() != 200, 'No success response');

        $data = json_decode($response->getBody()->getContents(), true);

        return new PositionResponseAdapter($data);
    }

    /**
     * @throws \Throwable
     */
    public function positionHistory(string $symbol, string $positionId): ?PositionResponseContract
    {
        $response = LaravelBitunixApi::getPendingPositions($symbol, $positionId);

        throw_if($response->getStatusCode() != 200, 'No success response');

        $data = json_decode($response->getBody()->getContents(), true);

        return new PositionHistoryResponseAdapter($data, $positionId);
    }

    /**
     * @throws \Throwable
     */
    public function orders(?string $symbol = null, ?array $orderIds = null): OrderListResponseContract
    {
        $response = LaravelBitunixApi::getPendingPositions($symbol);

        throw_if($response->getStatusCode() != 200, 'No success response');

        $data = json_decode($response->getBody()->getContents(), true);

        return new OrderListResponseAdapter($data);
    }

    /**
     * @throws \Throwable
     */
    public function setOrder(string $symbol, TypeEnum $typeEnum, SideEnum $sideEnum, SideEnum $positionSide, float $amount, float $price, mixed $client_id = null, ?Target $takeProfit = null, ?Target $stopLoss = null): ?SetOrderResponseContract
    {
        $side = Str::of($sideEnum->convertToBuySell())->upper()->toString();
        $orderType = Str::of($typeEnum->value)->upper()->toString();

        $response = LaravelBitunixApi::placeOrder(
            $symbol,
            (string) $amount,
            $side,
            'OPEN',
            $orderType,
            $price,
            null, // positionId
            null, // effect
            $client_id,
            null, // reduceOnly
            $takeProfit?->getPrice(), // tpPrice
            $takeProfit? 'LAST_PRICE' : null, // tpStopType
            $takeProfit ? 'LIMIT' : null, // tpOrderType
            $takeProfit?->getPrice(), // tpPrice
            $stopLoss?->getPrice(), // slPrice
            'MARK_PRICE', // slStopType
            'MARKET', // slOrderType
            $stopLoss?->getPrice(), // slPrice
        );

        throw_if($response->getStatusCode() != 200, 'No success response');

        $data = json_decode($response->getBody()->getContents(), true);

        return new OrderResponseAdapter($data);
    }

    public function convertedTimeframe(TimeframeEnum $timeframe): string
    {
        return $timeframe->toBitUniixFormat();
    }

    public function exchangeName(): string
    {
        return 'bitunix';
    }
}
