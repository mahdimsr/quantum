<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Notifications\SignalNotification;
use App\Services\Exchange\Facade\Exchange;
use App\Services\Indicator\Facade\Indicator;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class SimpleSignalCommand extends Command
{
    protected $signature = 'signal:simple {symbol} {timeframe} {from} {to}';

    protected $description = 'Calculate with RSI,EMA';

    private bool $isValidRSISignal = false;
    private bool $isEMAAscending   = false;

    public function handle()
    {
        $symbol    = $this->argument('symbol');
        $timeframe = $this->argument('timeframe');
        $from      = $this->argument('from');
        $to        = $this->argument('to');

        $response = Exchange::ohlc($symbol, $timeframe, $to, $from, 100);

        $candles = [];

        for ($i = 0; $i < $response->count(); $i++) {
            $candle = $response->ohlc($i);

            $candles[] = $candle;
        }

        $candlesCollection = collect($candles);

        $closePriceCollection = $candlesCollection->map(fn($item) => ['close' => $item->close()]);

        $rsiValue = Indicator::RSI($closePriceCollection->toArray());

        $this->setRSIFlag($rsiValue);

        $smallEMA = Indicator::EMA($closePriceCollection->toArray(), 50);
        $bigEMA   = Indicator::EMA($closePriceCollection->toArray(), 100);

        $lastSmallEMA = collect($smallEMA)->last()['val'];
        $lastBigEMA   = collect($bigEMA)->last()['val'];

        $this->setEMAFlag($lastSmallEMA, $lastBigEMA);


        if ($this->isEMAAscending and $this->isValidRSISignal) {

            $user = User::find(1);

            Notification::send($user, new SignalNotification($symbol, 'Long'));
        }

        if (!$this->isEMAAscending and $this->isValidRSISignal) {

            $user = User::find(1);

            Notification::send($user, new SignalNotification($symbol, 'Short'));
        }
    }

    private function setRSIFlag(mixed $rsiValue): void
    {
        $this->comment("RSI: $rsiValue");

        if ($rsiValue >= 45 and $rsiValue <= 55) {

            $this->isValidRSISignal = true;
        }
    }

    private function setEMAFlag(mixed $smallEMAValue, mixed $bigEMAValue)
    {
        if ($smallEMAValue > $bigEMAValue) {

            $this->comment("EMA: Ascending");
            $this->isEMAAscending = true;

        } else {

            $this->comment("EMA: Descending");
            $this->isEMAAscending = false;
        }
    }
}
