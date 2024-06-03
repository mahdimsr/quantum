<?php

namespace App\Services\Exchange\Repository;

class Order
{
    protected mixed $order_id;
    protected mixed $market;
    protected mixed $market_type;
    protected mixed $side;
    protected mixed $type;
    protected mixed $amount;
    protected mixed $price;
    protected mixed $client_id;
    protected mixed $created_at;
    protected mixed $updated_at;
    protected mixed $unfilled_amount;
    protected mixed $filled_amount;
    protected mixed $filled_value;
    protected mixed $fee;
    protected mixed $fee_ccy;
    protected mixed $maker_fee_rate;
    protected mixed $taker_fee_rate;

    public static function fromArray($data): self
    {
        $order = new Order();

        $order->setOrderId($data['order_id']);
        $order->setMarket($data['market']);
        $order->setMarketType($data['market_type']);
        $order->setSide($data['side']);
        $order->setType($data['type']);
        $order->setAmount($data['amount']);
        $order->setPrice($data['price']);
        $order->setClientId($data['client_id']);
        $order->setCreatedAt($data['created_at']);
        $order->setUpdatedAt($data['updated_at']);
        $order->setUnfilledAmount($data['unfilled_amount']);
        $order->setFilledAmount($data['filled_amount']);
        $order->setFilledValue($data['filled_value']);
        $order->setFee($data['fee']);
        $order->setFeeCcy($data['fee_ccy']);
        $order->setMakerFeeRate($data['maker_fee_rate']);
        $order->setTakerFeeRate($data['taker_fee_rate']);

        return $order;
    }

    public function getOrderId(): mixed
    {
        return $this->order_id;
    }

    public function setOrderId(mixed $order_id): void
    {
        $this->order_id = $order_id;
    }

    public function getMarket(): mixed
    {
        return $this->market;
    }

    public function setMarket(mixed $market): void
    {
        $this->market = $market;
    }

    public function getMarketType(): mixed
    {
        return $this->market_type;
    }

    public function setMarketType(mixed $market_type): void
    {
        $this->market_type = $market_type;
    }

    public function getSide(): mixed
    {
        return $this->side;
    }

    public function setSide(mixed $side): void
    {
        $this->side = $side;
    }

    public function getType(): mixed
    {
        return $this->type;
    }

    public function setType(mixed $type): void
    {
        $this->type = $type;
    }

    public function getAmount(): mixed
    {
        return $this->amount;
    }

    public function setAmount(mixed $amount): void
    {
        $this->amount = $amount;
    }

    public function getPrice(): mixed
    {
        return $this->price;
    }

    public function setPrice(mixed $price): void
    {
        $this->price = $price;
    }

    public function getClientId(): mixed
    {
        return $this->client_id;
    }

    public function setClientId(mixed $client_id): void
    {
        $this->client_id = $client_id;
    }

    public function getCreatedAt(): mixed
    {
        return $this->created_at;
    }

    public function setCreatedAt(mixed $created_at): void
    {
        $this->created_at = $created_at;
    }

    public function getUpdatedAt(): mixed
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(mixed $updated_at): void
    {
        $this->updated_at = $updated_at;
    }

    public function getUnfilledAmount(): mixed
    {
        return $this->unfilled_amount;
    }

    public function setUnfilledAmount(mixed $unfilled_amount): void
    {
        $this->unfilled_amount = $unfilled_amount;
    }

    public function getFilledAmount(): mixed
    {
        return $this->filled_amount;
    }

    public function setFilledAmount(mixed $filled_amount): void
    {
        $this->filled_amount = $filled_amount;
    }

    public function getFilledValue(): mixed
    {
        return $this->filled_value;
    }

    public function setFilledValue(mixed $filled_value): void
    {
        $this->filled_value = $filled_value;
    }

    public function getFee(): mixed
    {
        return $this->fee;
    }

    public function setFee(mixed $fee): void
    {
        $this->fee = $fee;
    }

    public function getFeeCcy(): mixed
    {
        return $this->fee_ccy;
    }

    public function setFeeCcy(mixed $fee_ccy): void
    {
        $this->fee_ccy = $fee_ccy;
    }

    public function getMakerFeeRate(): mixed
    {
        return $this->maker_fee_rate;
    }

    public function setMakerFeeRate(mixed $maker_fee_rate): void
    {
        $this->maker_fee_rate = $maker_fee_rate;
    }

    public function getTakerFeeRate(): mixed
    {
        return $this->taker_fee_rate;
    }

    public function setTakerFeeRate(mixed $taker_fee_rate): void
    {
        $this->taker_fee_rate = $taker_fee_rate;
    }
}
