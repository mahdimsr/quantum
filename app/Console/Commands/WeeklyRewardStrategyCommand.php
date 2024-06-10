<?php

namespace App\Console\Commands;

use App\Enums\StrategyEnum;
use App\Enums\TimeframeEnum;
use App\Models\Coin;
use App\Models\User;
use App\Notifications\SignalNotification;
use App\Services\Exchange\Facade\Exchange;
use App\Services\Strategy\UTBotAlertStrategy;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class WeeklyRewardStrategyCommand extends Command
{
    protected $signature = 'app:weekly-reward {coin}';

    protected $description = 'check position daily';

    protected Coin $coin;

    public function handle()
    {
        $this->coin = Coin::findByName($this->argument('coin'));

        $symbol = $this->coin->USDTSymbol();

        try {

            $dailyTimeframe = TimeframeEnum::DAILY->toCoinexFormat();
            $dailyCandlesResponse = Exchange::candles($symbol, $dailyTimeframe);

            $dailyUTBot = new UTBotAlertStrategy($dailyCandlesResponse->data(), 1);

            if ($this->validateSignal($dailyUTBot)) {

                $lastDailyExitingPosition = $dailyUTBot->lastPosition();
                $lastDailyCandle = $dailyCandlesResponse->data()->lastCandle();

                $weeklyTimeframe = TimeframeEnum::WEEKLY->toCoinexFormat();
                $weeklyCandleResponse = Exchange::candles($symbol, $weeklyTimeframe);
                $weeklyUTBot = new UTBotAlertStrategy($weeklyCandleResponse->data(), 1);

                $lastWeeklyPosition = $weeklyUTBot->lastPosition();

                if ($lastDailyExitingPosition->getMeta()['signal'] == $lastWeeklyPosition->getMeta()['signal']) {

                    $user = User::findByEmail('mahdi.msr4@gmail.com');

                    Notification::send($user, new SignalNotification($symbol,$lastDailyExitingPosition->getMeta()['signal'],StrategyEnum::WEEKLY_REWARD->value));
                }
            }


        } catch (\Exception $exception) {

            logs()->channel('strategy')->error($exception);

            $this->error("static strategy failed...");
        }
    }

    private function validateSignal(UTBotAlertStrategy $utbot): bool
    {
        $lasTriggerdPositionIndex = $utbot->getCalculatedCandles()->signals()->keys()->last();
        $currentPositionIndex = $utbot->getCalculatedCandles()->keys()->last();

        return ($currentPositionIndex - $lasTriggerdPositionIndex) <= 2;
    }

}
