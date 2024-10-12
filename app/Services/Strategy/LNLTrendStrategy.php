<?php

namespace App\Services\Strategy;

use App\Services\Exchange\Repository\CandleCollection;
use App\Services\Indicator\Facade\Indicator;

class LNLTrendStrategy
{
    private LNLTrendCollection $LNLTrendCollection;

    public function __construct(CandleCollection $candleCollection)
    {
        $this->LNLTrendCollection = LNLTrendCollection::make($candleCollection);
    }

    public function collection(): LNLTrendCollection
    {
        return $this->LNLTrendCollection;
    }
}
