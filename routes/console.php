<?php

use App\Console\Commands\CloseOrbitalOrdersCommand;
use App\Console\Commands\HarmonyPositionsCommand;
use App\Console\Commands\HarmonyStrategyCommand;
use App\Console\Commands\HarmonyTakeProfitCommand;
use App\Console\Commands\OrbitalStrategyCommand;
use App\Console\Commands\UpdateOrderPositionIdCommand;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Schedule::command(UpdateOrderPositionIdCommand::class)->everyMinute();

Schedule::command(OrbitalStrategyCommand::class)->everyThirtyMinutes();
Schedule::command(CloseOrbitalOrdersCommand::class)->everyThirtyMinutes();

Schedule::command(HarmonyStrategyCommand::class)->everyFiveMinutes()->appendOutputTo('logs/harmony/strategy.log');
Schedule::command(HarmonyTakeProfitCommand::class)->everyFiveMinutes()->appendOutputTo('logs/harmony/tp.log');
Schedule::command(HarmonyPositionsCommand::class)->hourlyAt([20, 50])->appendOutputTo('logs/harmony/position.log');
