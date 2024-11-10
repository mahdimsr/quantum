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
        $schedule->command('app:static-reward-strategy')->hourlyAt(15)->appendOutputTo(storage_path('logs/commands/static-reward.log'));

//        $schedule->command('app:close-position-command --timeBase')->hourlyAt(50)->appendOutputTo(storage_path('logs/commands/close-position.log'));

        $schedule->command('app:close-position-command --percentageBase')->everyMinute()->appendOutputTo(storage_path('logs/commands/close-position.log'));

        $schedule->call(function () {

            $coins = Coin::withStrategies(StrategyEnum::DYNAMIC_REWARD)->get();

            foreach ($coins as $coin) {

                Artisan::call('app:dynamic-reward-strategy', ['--coin' => $coin->name, '--leverage' => 20]);
            }

        })->hourlyAt(30)->appendOutputTo(storage_path('logs/commands/dynamic-reward.log'));


        $schedule->command('app:update-dynamic-reward-order')
            ->everyTenMinutes()
            ->appendOutputTo(storage_path('logs/commands/close-dynamic-reward.log'));

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
