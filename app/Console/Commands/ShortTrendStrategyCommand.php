<?php

namespace App\Console\Commands;

use App\Enums\StrategyEnum;
use App\Models\Coin;
use App\Models\User;
use App\Notifications\SignalNotification;
use App\Services\Exchange\Enums\TimeframeEnum;
use App\Services\Exchange\Facade\Exchange;
use App\Services\Exchange\Repository\CandleCollection;
use App\Services\Strategy\BollingerBandStrategy;
use App\Services\Strategy\UTBotAlertStrategy;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class ShortTrendStrategyCommand extends Command
{
    protected $signature = 'strategy:short-trend {coin} {timeframe=1h}';

    protected $description = 'Determine trend using ut-bot and enter by approve with bollinger-band';

    private Coin $coin;
    private CandleCollection $candleCollection;

    public function handle()
    {
        $this->coin = Coin::findByName($this->argument('coin'));
        $timeframe = TimeframeEnum::from($this->argument('timeframe'));

        $candlesResponse = Exchange::candles($this->coin->USDTSymbol(),$timeframe->toCoinexFormat());

        $this->candleCollection = $candlesResponse->data();

        $bollingerBandsStrategy = new BollingerBandStrategy($this->candleCollection);
        $utbotStrategy = new UTBotAlertStrategy($this->candleCollection);

        if ($utbotStrategy->buy() and $bollingerBandsStrategy->buy()) {

            Notification::send(User::mahdi(), new SignalNotification($this->coin->USDTSymbol(),'buy',StrategyEnum::SHORT_TREND->value));
        }

        if ($utbotStrategy->sell() and $bollingerBandsStrategy->sell()) {

            Notification::send(User::mahdi(), new SignalNotification($this->coin->USDTSymbol(),'sell',StrategyEnum::SHORT_TREND->value));
        }

    }
}
