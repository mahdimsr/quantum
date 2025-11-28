<?php

namespace App\Services\Strategy\Defaults;

use App\Enums\PositionTypeEnum;
use App\Services\Exchange\Repository\CandleCollection;
use App\Services\Strategy\LargeUtBotAlgorithm;
use App\Services\Strategy\LNLTrendAlgorithm;
use App\Services\Strategy\SmallUtBotAlgorithm;
use App\Services\Strategy\Strategy;
use App\Settings\HarmonySetting;

class HarmonyStrategy
{
    private HarmonySetting $setting;

    public function __construct()
    {
        $this->setting = app(HarmonySetting::class);
    }

    public function name(): string
    {
        return 'harmony';
    }

    public function signal(CandleCollection $candleCollection): ?PositionTypeEnum
    {
        $strategy = new Strategy();
        $strategy->send($candleCollection)->through([
            LNLTrendAlgorithm::class,
            SmallUtBotAlgorithm::class,
            LargeUtBotAlgorithm::class,
        ])->run();

        if ($strategy->hasShortEntry()) {
            return PositionTypeEnum::SHORT;
        }

        if ($strategy->hasLongEntry()) {
            return PositionTypeEnum::LONG;
        }

        return null;
    }

    public function active(): ?bool
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

    public function coins(): ?array
    {
        return $this->setting->coins;
    }

    public function maxPositions(): ?int
    {
        return $this->setting->max_positions;
    }

    public function takeProfitPercentage(): ?int
    {
        return $this->setting->tp_percent;
    }

    public function compound(): ?bool
    {
        return $this->setting->compound;
    }

    public function addToMargin(float $value): void
    {
        $margin = $this->setting->margin;
        $this->setting->margin = $margin + $value;
        $this->setting->save();
    }
}
