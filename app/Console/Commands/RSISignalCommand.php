<?php

namespace App\Console\Commands;

use App\Services\Exchange\Enums\ExchangeResolutionEnum;
use App\Services\Exchange\Facade\Exchange;
use App\Services\Indicator\Facade\Indicator;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class RSISignalCommand extends Command
{
    protected $signature = 'signal:rsi';

    protected $description = 'Calculate RSI and notify';

    public function handle()
    {
        $from = Carbon::now()->startOfMinute()->subHours(6);
        $to = Carbon::now()->startOfMinute();

        $diff = $to->diffInHours($from);

        $this->info("Getting Candles from $diff hours ago for BTC ...");

        $response = Exchange::ohlc('BTCUSDT',ExchangeResolutionEnum::EVERY_FIVE_MINUTES,$to->timestamp,$from->timestamp,100);

        $this->info('Calculating RSI ...');

        $data = [];

        for ($i = 0; $i < $response->count();$i++){
            $candle = $response->ohlc($i);

            $data[] = ['close' => $candle->close()];
        }

        $rsi = Indicator::RSI($data,14);

        $nowDateTimeString = $to->toDateTimeString();

        $this->info("RSI in $nowDateTimeString: $rsi");

        if ($rsi >= 50){
            $this->info('Buy :)');
        }else{
            $this->warn('Dont Buy !!!');
        }
    }
}
