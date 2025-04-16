<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('manda-strategy.margin', 10);
        $this->migrator->add('manda-strategy.leverage', 10);
        $this->migrator->add('manda-strategy.time_frame', '1h');
        $this->migrator->add('manda-strategy.sl_mode', 'away');
        $this->migrator->add('manda-strategy.close_on_reverse', true);
    }
};
