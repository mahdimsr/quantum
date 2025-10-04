<?php

namespace App\Services\Exchange\Repository;

use App\Services\Exchange\Enums\SideEnum;
use App\Services\Exchange\Enums\TypeEnum;
use Illuminate\Support\Str;

class Order
{
    protected mixed $order_id = null;
    protected mixed $client_id = null;
    protected ?string $symbol = null;
    protected ?SideEnum $side = null;
    protected ?TypeEnum $type = null;
    protected mixed $price = null;
    protected mixed $quantity = null;

    private function __construct()
    {
    }

    public static function create(?string $symbol, ?string $side, ?string $type, mixed $price, mixed $quantity, mixed $client_id = null, mixed $order_id = null): Order
    {
        $order = new self();

        $order->symbol = $symbol;
        $order->side = $side ? SideEnum::from(Str::of($side)->upper()->toString()) : null;
        $order->type = $type ? TypeEnum::from(Str::of($type)->upper()->toString()) : null;
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
