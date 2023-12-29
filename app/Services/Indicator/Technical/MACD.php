<?php

namespace App\Services\Indicator\Technical;

use App\Services\Indicator\Facade\Indicator;

class MACD
{
    protected static int $shortPeriod = 9;
    protected static int $longPeriod = 9;
    protected static int $signalPeriod = 9;

    public static function shortPeriod(int $shortPeriod): static
    {
        self::$shortPeriod = $shortPeriod;

        return new self();
    }

    public static function longPeriod(int $longPeriod): static
    {
        self::$longPeriod = $longPeriod;

        return new self();
    }

    public static function signalPeriod(int $signalPeriod): static
    {
        self::$signalPeriod = $signalPeriod;

        return new self();
    }

    public static function run(array $data): array
    {
        $shortEMA = Indicator::EMA($data, self::$shortPeriod);

        $longEMA = Indicator::EMA($data, self::$longPeriod);

        $macdLine = array_map(function ($short, $long) {
            return $short - $long;
        }, $shortEMA, $longEMA);

        $signalLine = Indicator::EMA($macdLine, self::$signalPeriod);

        return [
            'MACD_line' => $macdLine,
            'signal_line' => $signalLine
        ];
    }
}
