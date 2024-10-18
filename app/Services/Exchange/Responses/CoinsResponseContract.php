<?php

namespace App\Services\Exchange\Responses;

use App\Services\Exchange\Repository\CoinCollection;

interface CoinsResponseContract
{
    public function data(): ?CoinCollection;
}
