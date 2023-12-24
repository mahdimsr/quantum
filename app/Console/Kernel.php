<?php

namespace App\Console;

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
        $schedule->call(function () {

            $now       = Carbon::now();
            $yesterday = Carbon::now()->subHours(72);

            foreach (SymbolEnum::cases() as $case) {

                Artisan::call('signal:simple', [
                    'symbol'    => $case->toUSDT(),
                    'timeframe' => ExchangeResolutionEnum::EVERY_THIRTY_MINUTES->toSeconds(),
                    'from'      => $yesterday->timestamp,
                    'to'        => $now->timestamp
                ]);
            }

        })->everyFiveMinutes();
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
