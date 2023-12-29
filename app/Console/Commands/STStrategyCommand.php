<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Notifications\SignalNotification;
use App\Services\Exchange\Facade\Exchange;
use App\Services\Indicator\Facade\Indicator;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class STStrategyCommand extends Command
{

    protected $signature = 'signal:simple-triple {symbol} {timeframe} {from} {to}';

    protected $description = 'Simple Triple Indicators';

    public function handle()
    {
        $symbol = $this->argument('symbol');
        $timeframe = $this->argument('timeframe');
        $from = $this->argument('from');
        $to = $this->argument('to');

        $response = Exchange::ohlc($symbol, $timeframe, $to, $from, 100);

        $candles = [];

        for ($i = 0; $i < $response->count(); $i++) {
            $candle = $response->ohlc($i);

            $candles[] = $candle;
        }

        $closePriceArray = collect($candles)->map(fn($item) => $item->close())->toArray();

        // Calculate Trand

        $shortEMA = Indicator::EMA($closePriceArray,20);
        $longEMA = Indicator::EMA($closePriceArray,200);

        $lastShortEMA = collect($shortEMA)->last();
        $lastLongEMA   = collect($longEMA)->last();


        if ($lastShortEMA > $lastLongEMA){

            $this->ascendingSignal($closePriceArray);
        }else{

            $this->descendingSignal($closePriceArray);
        }
    }

    private function ascendingSignal(array $priceArray)
    {
        if ($this->RSIAccepted($priceArray)){

            $macdData = Indicator::MACD($priceArray);

            $macdLine = $macdData['MACD_line'];
            $macdSignal = $macdData['signal_line'];

            $lastMACDValue = collect($macdLine)->last();
            $lastMACDSignalValue = collect($macdSignal)->last();

            if ($lastMACDValue > $lastMACDSignalValue){

                $user = User::find(1);

                Notification::send($user, new SignalNotification($this->argument('symbol'), 'Long 🟢'));
            }
        }
    }

    private function descendingSignal(array $priceArray)
    {
        if ($this->RSIAccepted($priceArray)){

            $macdData = Indicator::MACD($priceArray);

            $macdLine = $macdData['MACD_line'];
            $macdSignal = $macdData['signal_line'];

            $lastMACDValue = collect($macdLine)->last();
            $lastMACDSignalValue = collect($macdSignal)->last();

            if ($lastMACDValue < $lastMACDSignalValue){

                $user = User::find(1);

                Notification::send($user, new SignalNotification($this->argument('symbol'), 'Short 🔴'));
            }
        }
    }

    private function RSIAccepted(array $priceArray): bool
    {
        $rsiValue = Indicator::RSI($priceArray);

        return $rsiValue > 45 and $rsiValue < 55;
    }


}
