<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('harmony-strategy.active', false);
        $this->migrator->add('harmony-strategy.margin', 0);
        $this->migrator->add('harmony-strategy.leverage', 0);
        $this->migrator->add('harmony-strategy.max_positions', 0);
        $this->migrator->add('harmony-strategy.coins', []);
        $this->migrator->add('harmony-strategy.compound', false);
        $this->migrator->add('harmony-strategy.tp_percent', 0);
    }

    public function down(): void
    {
        $this->migrator->delete('harmony-strategy.active');
        $this->migrator->delete('harmony-strategy.margin');
        $this->migrator->delete('harmony-strategy.leverage');
        $this->migrator->delete('harmony-strategy.max_positions');
        $this->migrator->delete('harmony-strategy.coins');
        $this->migrator->delete('harmony-strategy.compound');
        $this->migrator->delete('harmony-strategy.tp_percent');
    }
};
