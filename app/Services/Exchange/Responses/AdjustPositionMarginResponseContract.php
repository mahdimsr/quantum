<?php

namespace App\Services\Exchange\Responses;

interface AdjustPositionMarginResponseContract
{
    public function position_id(): mixed;

    public function market(): mixed;

    public function market_type(): mixed;

    public function side(): mixed;

    public function margin_mode(): mixed;

    public function open_interest(): mixed;

    public function close_avbl(): mixed;

    public function ath_position_amount(): mixed;

    public function unrealized_pnl(): mixed;

    public function realized_pnl(): mixed;

    public function avg_entry_price(): mixed;

    public function cml_position_value(): mixed;

    public function max_position_value(): mixed;

    public function take_profit_price(): mixed;

    public function stop_loss_price(): mixed;

    public function take_profit_type(): mixed;

    public function stop_loss_type(): mixed;

    public function leverage(): mixed;

    public function margin_avbl(): mixed;

    public function ath_margin_size(): mixed;

    public function position_margin_rate(): mixed;

    public function maintenance_margin_rate(): mixed;

    public function maintenance_margin_value(): mixed;

    public function liq_price(): mixed;

    public function brk_price(): mixed;

    public function adl_level(): mixed;

    public function settle_price(): mixed;

    public function settle_value(): mixed;

    public function created_at(): mixed;

    public function updated_at(): mixed;
}
