<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Notifications\SignalNotification;
use App\Services\Exchange\Facade\Exchange;
use App\Services\Indicator\Facade\Indicator;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class RSIComparisonCommand extends Command
{

    protected $signature = 'signal:rsi-compare {symbol} {timeframe} {from} {to}';


    protected $description = 'Compare two rsi and signal';


    public function handle()
    {
        $symbol = $this->argument('symbol');
        $timeframe = $this->argument('timeframe');
        $from = $this->argument('from');
        $to = $this->argument('to');

        $response = Exchange::ohlc($symbol, $timeframe, $to, $from, 100);

        $closePriceArray = $response->all()->map(fn($item) => $item->close())->toArray();

        $ema100 = Indicator::EMA($closePriceArray,100);
        $ema25 = Indicator::EMA($closePriceArray,25);

        $position = 'Long 🟢';

        if ($ema25 < $ema100){
            $position = 'Short 🔴';
        }

        $rsi100 = Indicator::RSI($closePriceArray, 100);
        $rsi25 = Indicator::RSI($closePriceArray, 25);

        $compare = $rsi25 - $rsi100;

        if (1 <= abs($compare) and abs($compare) <= 5 ){

            $user = User::find(1);

            Notification::send($user, new SignalNotification($this->argument('symbol'), $position));
        }


    }
}
