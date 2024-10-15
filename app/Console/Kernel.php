<?php

namespace App\Console;

use App\Console\Commands\StaticRewardCommand;
use App\Enums\CoinEnum;
use App\Enums\CoinStatusEnum;
use App\Enums\StrategyEnum;
use App\Enums\TimeframeEnum;
use App\Models\Coin;
use App\Services\Exchange\Enums\ExchangeResolutionEnum;
use App\Services\Exchange\Enums\SymbolEnum;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Artisan;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $coins = Coin::all();

        foreach ($coins as $coin) {

            $schedule->command('app:static-reward-strategy', [$coin->name])->everyThirtyMinutes()->appendOutputTo(storage_path('logs/commands/static-reward.log'));
        }
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
