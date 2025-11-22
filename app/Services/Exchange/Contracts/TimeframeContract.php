<?php

namespace App\Services\Exchange\Contracts;

use App\Services\Exchange\Enums\TimeframeEnum;

interface TimeframeContract
{
    public function convertedTimeframe(TimeframeEnum $timeframe): string;
}
