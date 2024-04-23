<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Notifications\SignalNotification;
use App\Services\Exchange\Facade\Exchange;
use App\Services\Indicator\Entity\Candle;
use App\Services\Indicator\Facade\Indicator;
use App\Services\OrderService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class RSIComparisonCommand extends Command
{

    protected $signature = 'signal:rsi-compare {symbol} {timeframe} {from} {to}';


    protected $description = 'Compare two rsi and signal';


    /**
     * @throws \Exception
     */
    public function handle()
    {
        $symbol = $this->argument('symbol');
        $timeframe = $this->argument('timeframe');
        $from = $this->argument('from');
        $to = $this->argument('to');

        $response = Exchange::ohlc($symbol, $timeframe, $to, $from, 100);

        $candlesCollection = $response->all()->map(fn($item) => Candle::fromArray([
            'time' => $item->time(),
            'close' => $item->close(),
            'open' => $item->open(),
            'high' => $item->high(),
            'low' => $item->low(),
            'volume' => $item->volume()
        ]));

        $rsi100 = Indicator::RSI($candlesCollection, 100);
        $rsi25 = Indicator::RSI($candlesCollection, 25);

        $compare = $rsi25 - $rsi100;

        if (1 <= abs($compare) and abs($compare) <= 3) {

            $user = User::find(1);

            $ema100 = Indicator::EMA($candlesCollection, 100);
            $ema25 = Indicator::EMA($candlesCollection, 25);

            $lastPrice = $candlesCollection->last()->getClose();

            if ($ema25 < $ema100) {

                $orderDetails = OrderService::positionType('short')->priceCalculate($lastPrice,0.1,0.2);

                Notification::send($user, new SignalNotification($this->argument('symbol'), 'short',$lastPrice,$orderDetails['takeProfit'], $orderDetails['stopLoss']));

            } else {

                $orderDetails = OrderService::positionType('long')->priceCalculate($lastPrice,0.1,0.2);

                Notification::send($user, new SignalNotification($this->argument('symbol'), 'long',$lastPrice,$orderDetails['takeProfit'], $orderDetails['stopLoss']));

            }
        }


    }
}
