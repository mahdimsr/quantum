<?php

namespace App\Services\Strategy\Defaults;

use App\Enums\PositionTypeEnum;
use App\Services\Exchange\Repository\CandleCollection;
use App\Services\Indicator\Strategy\UTBotAlertStrategy;
use App\Services\Strategy\LNLTrendAlgorithm;
use App\Services\Strategy\SmallUtBotAlgorithm;
use App\Services\Strategy\Strategy;
use App\Settings\OrbitalStrategySetting;

class OrbitalStrategy
{
    private OrbitalStrategySetting $setting;

    public function __construct()
    {
        $this->setting = app(OrbitalStrategySetting::class);
    }

    public function name(): string
    {
        return 'orbital';
    }

    public function signal(CandleCollection $candleCollection): ?PositionTypeEnum
    {
        $strategy = new Strategy();
        $strategy->send($candleCollection)->through([
            LNLTrendAlgorithm::class,
            SmallUtBotAlgorithm::class
        ])->run();

        if ($strategy->hasShortEntry()) {
            return PositionTypeEnum::SHORT;
        }

        if ($strategy->hasLongEntry()) {
            return PositionTypeEnum::LONG;
        }

        return null;
    }

    public function active(): bool
    {
        return $this->setting->active;
    }

    public function margin(): ?int
    {
        return $this->setting->margin;
    }

    public function leverage(): ?int
    {
        return $this->setting->leverage;
    }

    public function timeframe(): ?string
    {
        return $this->setting->timeframe;
    }

    public function coins(): ?array
    {
        return $this->setting->coins;
    }

    public function stopLossType(): ?string
    {
        return $this->setting->stopLossType;
    }

    public function autoClose(): ?bool
    {
        return $this->setting->autoClose;
    }
}
