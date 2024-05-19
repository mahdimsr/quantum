<?php

namespace App\Services\Exchange\Responses;

interface ClosePositionResponseContract
{
    public function order_id(): mixed;
    public function market(): mixed;
    public function market_type(): mixed;
    public function side(): mixed;
    public function type(): mixed;
    public function amount(): mixed;
    public function price(): mixed;
    public function unfilled_amount(): mixed;
    public function filled_amount(): mixed;
    public function filled_value(): mixed;
    public function client_id(): mixed;
    public function fee(): mixed;
    public function fee_ccy(): mixed;
    public function maker_fee_rate(): mixed;
    public function taker_fee_rate(): mixed;
    public function last_filled_amount(): mixed;
    public function last_filled_price(): mixed;
    public function realized_pnl(): mixed;
    public function created_at(): mixed;
    public function updated_at(): mixed;
}
