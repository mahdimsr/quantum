<?php

namespace App\Observers;

use App\Models\Coin;

class CoinObserver
{

    public function deleting(Coin $coin): void
    {
        $coin->coinStrategies()->delete();
    }

}
