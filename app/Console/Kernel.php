<?php

namespace App\Console;

use App\Console\Commands\StaticRewardCommand;
use App\Enums\CoinEnum;
use App\Enums\CoinStatusEnum;
use App\Enums\OrderStatusEnum;
use App\Enums\StrategyEnum;
use App\Enums\TimeframeEnum;
use App\Models\Coin;
use App\Models\Order;
use App\Services\Exchange\Enums\ExchangeResolutionEnum;
use App\Services\Exchange\Enums\SymbolEnum;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Queue;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('app:static-reward-strategy')->hourly()->appendOutputTo(storage_path('logs/commands/static-reward.log'));


        $schedule->command('app:close-position-command')->hourlyAt(45)->appendOutputTo(storage_path('logs/commands/close-position.log'));

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
