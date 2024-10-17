<?php

namespace App\Services\Exchange\Repository;

class Position
{
    protected ?string $orderId;
    protected string $positionId;
    protected string $symbol;
    protected string $unrealizeProfit;
    protected string $realizeProfit;
    protected string $markPrice;
    protected string $pnlRatio;

    private function __construct()
    {

    }

    public static function create(array $item): self
    {
        $position = new self();

        $position->orderId = array_key_exists('orderId', $item) ? $item['orderId'] : null;
        $position->positionId = $item['positionId'];
        $position->symbol = $item['symbol'];
        $position->unrealizeProfit = $item['unrealizedProfit'];
        $position->realizeProfit = $item['realizedProfit'];
        $position->markPrice = $item['markPrice'];
        $position->pnlRatio = $item['pnlRatio'];

        return $position;
    }

    public function getPositionId(): string
    {
        return $this->positionId;
    }

    public function getSymbol(): string
    {
        return $this->symbol;
    }

    public function getUnrealizeProfit(): string
    {
        return $this->unrealizeProfit;
    }

    public function getRealizeProfit(): string
    {
        return $this->realizeProfit;
    }

    public function getMarkPrice(): string
    {
        return $this->markPrice;
    }

    public function pnlPercent(): string
    {
        return $this->pnlRatio * 100;
    }

    public function getOrderId(): ?string
    {
        return $this->orderId;
    }
}
