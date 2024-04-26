<?php

namespace App\Console\Commands;

use App\Enums\CoinEnum;
use App\Enums\TimeframeEnum;
use App\Services\Exchange\Facade\Exchange;
use App\Services\Indicator\Facade\Calculate;
use App\Services\Indicator\Facade\Indicator;
use App\Traits\CommandSuccessOutput;
use Illuminate\Console\Command;

class BollingerBandsSignalCommand extends Command
{
    use CommandSuccessOutput;

    protected $signature = 'indicator:bollinger-bands {coin} {--T|timeframe=4H}';

    protected $description = 'Bollinger Band Signal Command';

    public function handle()
    {
        $coin = $this->argument('coin');
        $timeframe = $this->option('timeframe');

        $symbol = CoinEnum::from($coin)->USDTSymbol();
        $timeframe = TimeframeEnum::from($timeframe)->toCoinexFormat();

        try {

            $this->info("getting market data of $symbol in $timeframe period");

            $marketResponse = Exchange::market($symbol,$timeframe);

            $bollingerBands = Indicator::BollingerBands($marketResponse->data());
            $lastBollingerBand = collect($bollingerBands)->last();

            $upperBand = $lastBollingerBand['upper_band'];
            $lowerBand = $lastBollingerBand['lower_band'];

            $lastCandle = $marketResponse->data()->lastCandle();

            $lastHighPrice = $lastCandle->getHigh();
            $lastLowPrice = $lastCandle->getLow();

            if (Calculate::touched($lastHighPrice,$upperBand)){

                // TODO: signal to short
            }

            if (Calculate::touched($lastLowPrice,$lowerBand)){

                // TODO: signal to buy
            }


            $this->success("upper: $upperBand and lower: $lowerBand");

        }catch (\Exception $exception){

            logs()->critical($exception);

            $this->error("exception fired for $symbol in $timeframe period");
        }



    }
}
