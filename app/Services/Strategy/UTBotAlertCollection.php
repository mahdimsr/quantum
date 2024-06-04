<?php

namespace App\Services\Strategy;

use App\Services\Exchange\Repository\Candle;
use App\Services\Exchange\Repository\CandleCollection;

class UTBotAlertCollection extends CandleCollection
{
    public function signals(): self
    {
        $array = $this->toArray();

        return $this->filter(function (Candle $candle, int $index) use ($array) {

            if (array_key_exists($index-1, $array)) {

                $preCandle = $array[$index-1];

                if (array_key_exists('signal', $candle->getMeta()) and array_key_exists('signal', $preCandle->getMeta())) {

                    return $candle->getMeta()['signal'] != $preCandle->getMeta()['signal'];
                }
            }

            return false;
        });
    }
}
