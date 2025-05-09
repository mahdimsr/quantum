<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class OrbitalStrategySetting extends Settings
{
    public ?bool $active = false;
    public ?int $margin = null;
    public ?int $leverage = null;
    public ?string $timeframe = null;
    public array $coins = [];
    public ?string $stopLossType = null;
    public ?bool $autoClose = false;

    public static function group(): string
    {
        return 'orbital-strategy';
    }
}
