<?php

namespace App\Console\Commands;

use App\Enums\CoinEnum;
use App\Enums\TimeframeEnum;
use App\Models\User;
use App\Notifications\BollingerBandsNotification;
use App\Services\Exchange\Facade\Exchange;
use App\Services\Indicator\Facade\Calculate;
use App\Services\Indicator\Facade\Indicator;
use App\Traits\CommandSuccessOutput;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class BollingerBandsSignalCommand extends Command
{
    use CommandSuccessOutput;

    protected $signature = 'indicator:bollinger-bands {coin} {--T|timeframe=4H} {--RSI : calculate rsi to be more confident of signal}';

    protected $description = 'Bollinger Band Signal Command';

    public function handle()
    {
        $coin = $this->argument('coin');
        $timeframe = $this->option('timeframe');

        $symbol = CoinEnum::from($coin)->USDTSymbol();
        $timeframe = TimeframeEnum::from($timeframe)->toCoinexFormat();

        try {

            $this->info("getting market data of $symbol in $timeframe period");

            $marketResponse = Exchange::candles($symbol, $timeframe);

            $bollingerBands = Indicator::BollingerBands($marketResponse->data());
            $lastBollingerBand = collect($bollingerBands)->last();

            $upperBand = $lastBollingerBand['upper_band'];
            $lowerBand = $lastBollingerBand['lower_band'];

            $lastCandle = $marketResponse->data()->lastCandle();

            $lastHighPrice = $lastCandle->getHigh();
            $lastLowPrice = $lastCandle->getLow();

            if ($this->option('RSI')){

                $rsi = Indicator::RSI($marketResponse->data());

                if ($this->isLowRSI($rsi) and $this->isLowBollingerBands($lastLowPrice, $lowerBand)){

                    $this->sendLongSignal();
                }

                if ($this->isHighRSI($rsi) and $this->isHighBollingerBands($lastHighPrice, $upperBand)){

                    $this->sendShortSignal();
                }

            }else{

                if ($this->isLowBollingerBands($lastLowPrice, $lowerBand)){

                    $this->sendLongSignal();
                }

                if ($this->isHighBollingerBands($lastHighPrice, $upperBand)){

                    $this->sendShortSignal();
                }
            }

            if (Calculate::touched($lastLowPrice, $lowerBand)) {

            }

        } catch (\Exception $exception) {

            logs()->critical($exception);

            $this->error("exception fired for $symbol in $timeframe period");
        }
    }

    private function isLowBollingerBands($lastLowPrice, $lowerBand): bool
    {
        return Calculate::touched($lastLowPrice, $lowerBand);
    }

    private function isHighBollingerBands($lastHighPrice, $upperBand): bool
    {
        return Calculate::touched($lastHighPrice, $upperBand);
    }

    private function isLowRSI($rsi): bool
    {
        return Calculate::touchedByRange($rsi,30,5);
    }

    private function isHighRSI($rsi): bool
    {
        return Calculate::touchedByRange($rsi,70,5);
    }

    private function sendLongSignal(): void
    {
        $coin = $this->argument('coin');
        $symbol = CoinEnum::from($coin)->USDTSymbol();

        $user = User::findByEmail('mahdi.msr4@gmail.com');

        Notification::send($user, new BollingerBandsNotification($symbol, 'long'));

        logs()->info("Long notification sent for $symbol");

        $this->success("Long notification sent for $symbol");
    }

    private function sendShortSignal(): void
    {
        $coin = $this->argument('coin');
        $symbol = CoinEnum::from($coin)->USDTSymbol();

        $user = User::findByEmail('mahdi.msr4@gmail.com');

        Notification::send($user, new BollingerBandsNotification($symbol, 'short'));

        logs()->info("Short notification sent for $symbol");

        $this->success("Short notification sent for $symbol");
    }
}
