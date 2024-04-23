<?php

namespace App\Console\Commands;

use App\Enums\CoinEnum;
use App\Enums\TimeframeEnum;
use App\Services\Exchange\Facade\Exchange;
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

            $this->success("bollinger-bands calculated");

        }catch (\Exception $exception){

            logs()->critical($exception);

            $this->error("exception fired for $symbol in $timeframe period");
        }



    }
}
