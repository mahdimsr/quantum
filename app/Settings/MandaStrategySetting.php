<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class MandaStrategySetting extends Settings
{
    public int $margin;
    public int $leverage;
    public string $time_frame;
    public string $sl_mode;
    public string $close_on_reverse;

    public static function group(): string
    {
        return 'manda-strategy';
    }
}
