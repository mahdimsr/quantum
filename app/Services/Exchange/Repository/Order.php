<?php

namespace App\Services\Exchange\Repository;

use App\Services\Exchange\Enums\SideEnum;
use App\Services\Exchange\Enums\TypeEnum;

class Order
{
    protected mixed $order_id;
    protected mixed $client_id;
    protected string $symbol;
    protected SideEnum $side;
    protected TypeEnum $type;
    protected mixed $price;
    protected mixed $quantity;

    private function __construct()
    {
    }

    public static function create(string $symbol, string $side, string $type, mixed $price, mixed $quantity, mixed $client_id = null, mixed $order_id = null): Order
    {
        $order = new self();

        $order->symbol = $symbol;
        $order->side = SideEnum::from($side);
        $order->type = TypeEnum::from($type);
        $order->price = $price;
        $order->quantity = $quantity;
        $order->client_id = $client_id;
        $order->order_id = $order_id;

        return $order;
    }

    public function getOrderId(): mixed
    {
        return $this->order_id;
    }

    public function getClientId(): mixed
    {
        return $this->client_id;
    }

    public function getSymbol(): string
    {
        return $this->symbol;
    }

    public function getSide(): SideEnum
    {
        return $this->side;
    }

    public function getType(): TypeEnum
    {
        return $this->type;
    }

    public function getPrice(): mixed
    {
        return $this->price;
    }

    public function getQuantity(): mixed
    {
        return $this->quantity;
    }


}
