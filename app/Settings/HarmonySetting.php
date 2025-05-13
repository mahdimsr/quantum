<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class HarmonySetting extends Settings
{
    public ?bool $active = false;
    public ?int $margin = 0;
    public ?int $leverage = 0;
    public ?int $max_positions = 0;
    public ?array $coins = [];
    public ?bool $compound = false;
    public ?int $tp_percent = 0;

    public static function group(): string
    {
        return 'harmony-strategy';
    }
}
