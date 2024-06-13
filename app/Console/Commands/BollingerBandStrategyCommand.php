<?php

namespace App\Console\Commands;

use App\Enums\StrategyEnum;
use App\Enums\TimeframeEnum;
use App\Models\Coin;
use App\Models\User;
use App\Notifications\SignalNotification;
use App\Services\Exchange\Facade\Exchange;
use App\Services\Exchange\Repository\CandleCollection;
use App\Services\Strategy\BollingerBandStrategy;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class BollingerBandStrategyCommand extends Command
{
    protected $signature = 'strategy:bollinger-band {coin} {timeframe=4H}';

    protected $description = 'Check if trend is in up/low band';

    private Coin $coin;
    private CandleCollection $candleCollection;

    public function handle()
    {
        $this->coin = Coin::findByName($this->argument('coin'));
        $timeframe = TimeframeEnum::from($this->argument('timeframe'));

        $candlesResponse = Exchange::candles($this->coin->USDTSymbol(),$timeframe->toCoinexFormat());

        $this->candleCollection = $candlesResponse->data();

        $bollingerBandsStrategy = new BollingerBandStrategy($this->candleCollection);

        if ($bollingerBandsStrategy->buy()) {

            Notification::send(User::mahdi(), new SignalNotification($this->coin->USDTSymbol(),'buy',StrategyEnum::SIMPLE_BOLLINGER_BAND->value));
        }

        if ($bollingerBandsStrategy->sell()) {

            Notification::send(User::mahdi(), new SignalNotification($this->coin->USDTSymbol(),'sell',StrategyEnum::SIMPLE_BOLLINGER_BAND->value));

        }

    }
}
