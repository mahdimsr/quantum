<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class AppSetting extends Settings
{
    public ?int $leverage = 5;

    public static function group(): string
    {
        return 'app';
    }
}
