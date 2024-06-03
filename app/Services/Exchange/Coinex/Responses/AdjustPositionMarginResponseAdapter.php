<?php

namespace App\Services\Exchange\Coinex\Responses;

use App\Services\Exchange\Responses\AdjustPositionMarginResponseContract;

class AdjustPositionMarginResponseAdapter implements AdjustPositionMarginResponseContract
{
    private array $response;

    public function __construct(array $response)
    {
        $this->response = $response;
    }

    public function position_id(): mixed
    {
        return $this->response['data']['position_id'];
    }

    public function market(): mixed
    {
        return $this->response['data']['market'];
    }

    public function market_type(): mixed
    {
        return $this->response['data']['market_type'];
    }

    public function side(): mixed
    {
        return $this->response['data'][')'];
    }

    public function margin_mode(): mixed
    {
        return $this->response['data']['margin_mode'];
    }

    public function open_interest(): mixed
    {
        return $this->response['data']['open_interest'];
    }

    public function close_avbl(): mixed
    {
        return $this->response['data']['close_avbl'];
    }

    public function ath_position_amount(): mixed
    {
        return $this->response['data']['ath_position_amount'];
    }

    public function unrealized_pnl(): mixed
    {
        return $this->response['data']['unrealized_pnl'];
    }

    public function realized_pnl(): mixed
    {
        return $this->response['data']['realized_pnl'];
    }

    public function avg_entry_price(): mixed
    {
        return $this->response['data']['avg_entry_price'];
    }

    public function cml_position_value(): mixed
    {
        return $this->response['data']['cml_position_value'];
    }

    public function max_position_value(): mixed
    {
        return $this->response['data']['max_position_value'];
    }

    public function take_profit_price(): mixed
    {
        return $this->response['data']['take_profit_price'];
    }

    public function stop_loss_price(): mixed
    {
        return $this->response['data']['stop_loss_price'];
    }

    public function take_profit_type(): mixed
    {
        return $this->response['data']['take_profit_type'];
    }

    public function stop_loss_type(): mixed
    {
        return $this->response['data']['stop_loss_type'];
    }

    public function leverage(): mixed
    {
        return $this->response['data']['leverage'];
    }

    public function margin_avbl(): mixed
    {
        return $this->response['data']['margin_avbl'];
    }

    public function ath_margin_size(): mixed
    {
        return $this->response['data']['ath_margin_size'];
    }

    public function position_margin_rate(): mixed
    {
        return $this->response['data']['position_margin_rate'];
    }

    public function maintenance_margin_rate(): mixed
    {
        return $this->response['data']['maintenance_margin_rate'];
    }

    public function maintenance_margin_value(): mixed
    {
        return $this->response['data']['maintenance_margin_value'];
    }

    public function liq_price(): mixed
    {
        return $this->response['data']['liq_price'];
    }

    public function brk_price(): mixed
    {
        return $this->response['data']['brk_price'];
    }

    public function adl_level(): mixed
    {
        return $this->response['data']['adl_level'];
    }

    public function settle_price(): mixed
    {
        return $this->response['data']['settle_price'];
    }

    public function settle_value(): mixed
    {
        return $this->response['data']['settle_value'];
    }

    public function created_at(): mixed
    {
        return $this->response['data']['created_at'];
    }

    public function updated_at(): mixed
    {
        return $this->response['data']['updated_at'];
    }
}
