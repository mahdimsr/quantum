<?php

namespace App\Console\Commands;

use App\Enums\CoinEnum;
use App\Enums\TimeframeEnum;
use App\Models\Coin;
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
        $coin = Coin::findByName($this->argument('coin'));
        $symbol = $coin->USDTSymbol();

        $timeframe = $this->option('timeframe');
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

                if ($this->isLowRSI($rsi) and $this->isLowBollingerBands($lastLowPrice, $lowerBand, $coin->percent_tolerance)){

                    $this->sendLongSignal($lastLowPrice);
                }

                if ($this->isHighRSI($rsi) and $this->isHighBollingerBands($lastHighPrice, $upperBand, $coin->percent_tolerance)){

                    $this->sendShortSignal($lastHighPrice);
                }

            }else{

                $ema = collect(Indicator::EMA($marketResponse->data()))->first();

                if ($this->isUpperEMA($ema,$lastLowPrice) and $this->isLowBollingerBands($lastLowPrice, $lowerBand, $coin->percent_tolerance)) {

                    $this->sendLongSignal($lastLowPrice);
                }

                if ($this->isBelowEMA($ema,$lastHighPrice) and $this->isHighBollingerBands($lastHighPrice, $upperBand, $coin->percent_tolerance)) {

                    $this->sendShortSignal($lastHighPrice);
                }
            }


        } catch (\Exception $exception) {

            logs()->critical($exception);

            $this->error("exception fired for $symbol in $timeframe period");
        }
    }

    private function isLowBollingerBands($lastLowPrice, $lowerBand, $tolerance): bool
    {
        return Calculate::touched($lastLowPrice, $lowerBand, $tolerance);
    }

    private function isHighBollingerBands($lastHighPrice, $upperBand, $tolerance): bool
    {
        return Calculate::touched($lastHighPrice, $upperBand, $tolerance);
    }

    private function isLowRSI($rsi): bool
    {
        return Calculate::touchedByRange($rsi,30,5);
    }

    private function isHighRSI($rsi): bool
    {
        return Calculate::touchedByRange($rsi,70,5);
    }

    public function isUpperEMA($ema, $price): bool
    {
        return $price >= $ema;
    }

    public function isBelowEMA($ema, $price): bool
    {
        return $price <= $ema;
    }

    private function sendLongSignal($price): void
    {
        $coin = $this->argument('coin');
        $symbol = CoinEnum::from($coin)->USDTSymbol();

        $user = User::findByEmail('mahdi.msr4@gmail.com');

        Notification::send($user, new BollingerBandsNotification($symbol, 'long', $price));

        $this->success("Long notification sent for $symbol");
    }

    private function sendShortSignal($price): void
    {
        $coin = $this->argument('coin');
        $symbol = CoinEnum::from($coin)->USDTSymbol();

        $user = User::findByEmail('mahdi.msr4@gmail.com');

        Notification::send($user, new BollingerBandsNotification($symbol, 'short', $price));

        $this->success("Short notification sent for $symbol");
    }
}
