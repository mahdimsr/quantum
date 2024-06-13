<?php

namespace App\Console;

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
        $schedule->call(function () {

            $bollingerBandCoins = Coin::strategy(StrategyEnum::SIMPLE_BOLLINGER_BAND)
                ->status(CoinStatusEnum::AVAILABLE)
                ->get();

            foreach ($bollingerBandCoins as $coin) {

                Artisan::call('strategy:bollinger-band',[
                    'coin' => $coin->name,
                    'timeframe' => TimeframeEnum::EVERY_HOUR,
                ]);
            }

            $utBotCoins = Coin::strategy(StrategyEnum::UT_BOT_ALERT)
                ->status(CoinStatusEnum::AVAILABLE)
                ->get();



            foreach ($bollingerBandCoins as $coin) {

                Artisan::call('strategy:utbot',[
                    'coin' => $coin->name,
                    'timeframe' => TimeframeEnum::EVERY_HOUR,
                ]);
            }

        })->hourlyAt(15);
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
