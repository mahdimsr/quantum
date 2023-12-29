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
        $multiplier = 2 / (self::$period + 1);
        $ema = [];
        $ema[0] = $data[0];

        for ($i = 1; $i < count($data); $i++) {
            $ema[$i] = ($data[$i] - $ema[$i - 1]) * $multiplier + $ema[$i - 1];
        }

        return $ema;
    }
}
