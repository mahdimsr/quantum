<?php

namespace App\Console;

use App\Console\Commands\CloseOrbitalOrdersCommand;
use App\Console\Commands\OrbitalStrategyCommand;
use App\Console\Commands\UpdateOrderPositionIdCommand;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command(UpdateOrderPositionIdCommand::class)->everyMinute();

        $schedule->command(OrbitalStrategyCommand::class)->everyThirtyMinutes();
        $schedule->command(CloseOrbitalOrdersCommand::class)->everyThirtyMinutes();

    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
