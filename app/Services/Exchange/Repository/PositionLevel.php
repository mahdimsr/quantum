<?php

namespace App\Services\Exchange\Repository;

class PositionLevel
{
    private mixed $amount;
    private mixed $leverage;
    private mixed $maintenance_margin_rate;
    private mixed $min_initial_margin_rate;

    public static function fromArray($data): self
    {
        $positionLevel = new PositionLevel();

        $positionLevel->setAmount($data['amount']);
        $positionLevel->setLeverage($data['leverage']);
        $positionLevel->setMaintenanceMarginRate($data['maintenance_margin_rate']);
        $positionLevel->setMinInitialMarginRate($data['min_initial_margin_rate']);

        return $positionLevel;
    }

    public function getAmount(): mixed
    {
        return $this->amount;
    }

    public function setAmount(mixed $amount): void
    {
        $this->amount = $amount;
    }

    public function getLeverage(): mixed
    {
        return $this->leverage;
    }

    public function setLeverage(mixed $leverage): void
    {
        $this->leverage = $leverage;
    }

    public function getMaintenanceMarginRate(): mixed
    {
        return $this->maintenance_margin_rate;
    }

    public function setMaintenanceMarginRate(mixed $maintenance_margin_rate): void
    {
        $this->maintenance_margin_rate = $maintenance_margin_rate;
    }

    public function getMinInitialMarginRate(): mixed
    {
        return $this->min_initial_margin_rate;
    }

    public function setMinInitialMarginRate(mixed $min_initial_margin_rate): void
    {
        $this->min_initial_margin_rate = $min_initial_margin_rate;
    }


}
