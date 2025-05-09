<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('orbital-strategy.active', false);
        $this->migrator->add('orbital-strategy.margin', 0);
        $this->migrator->add('orbital-strategy.leverage', 0);
        $this->migrator->add('orbital-strategy.timeframe', null);
        $this->migrator->add('orbital-strategy.coins', []);
        $this->migrator->add('orbital-strategy.stopLossType', null);
        $this->migrator->add('orbital-strategy.autoClose', false);
    }

    public function down(): void
    {
        $this->migrator->delete('orbital-strategy.margin');
        $this->migrator->delete('orbital-strategy.leverage');
        $this->migrator->delete('orbital-strategy.timeframe');
        $this->migrator->delete('orbital-strategy.coins');
        $this->migrator->delete('orbital-strategy.stopLossType');
        $this->migrator->delete('orbital-strategy.autoClose');
    }
};
