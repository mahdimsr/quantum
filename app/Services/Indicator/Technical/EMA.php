<?php

namespace App\Services\Indicator\Technical;

class EMA
{
    protected static int $period = 9;

    public static function period(int $period): static
    {
        self::$period = $period;

        return new self();
    }

    public static function run(array $data): array
    {
        $smoothing_constant = 2 / (self::$period + 1);
        $previous_EMA = null;

        //loop data
        foreach($data as $key => $row){

            //skip init rows
            if ($key >= self::$period){

                //first
                if(!isset($previous_EMA)){
                    $sum = 0;
                    for ($i = $key - (self::$period-1); $i <= $key; $i ++)
                        $sum += $data[$i]['close'];
                    //calc sma
                    $sma = $sum / self::$period;

                    //save
                    $data[$key]['val'] = $sma;
                    $previous_EMA = $sma;
                }else{
                    //ema formula
                    $ema = ($data[$key]['close'] - $previous_EMA) * $smoothing_constant + $previous_EMA;

                    //save
                    $data[$key]['val'] = $ema;
                    $previous_EMA = $ema;
                }
            }
        }
        return $data;
    }
}
