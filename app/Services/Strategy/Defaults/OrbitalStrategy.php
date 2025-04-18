<?php

namespace App\Services\Strategy\Defaults;

use App\Services\Exchange\Repository\CandleCollection;
use App\Services\Strategy\LNLTrendAlgorithm;
use App\Services\Strategy\SmallUtBotAlgorithm;
use App\Services\Strategy\Strategy;
use App\Settings\OrbitalStrategySetting;

class OrbitalStrategy
{
    private CandleCollection $candlesCollection;
    private Strategy $strategy;
    private OrbitalStrategySetting $setting;

    public function __construct(CandleCollection $candlesCollection)
    {
        $this->candlesCollection = $candlesCollection;
        $this->setting = app(OrbitalStrategySetting::class);
        $this->strategy = new Strategy();
        $this->strategy->send($this->candlesCollection)->through([
            LNLTrendAlgorithm::class,
            SmallUtBotAlgorithm::class
        ])->run();
    }

    public function short(): bool
    {
        return $this->strategy->hasShortEntry();
    }

    public function long(): bool
    {
        return $this->strategy->hasLongEntry();
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
