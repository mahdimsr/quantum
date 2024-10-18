<?php

namespace App\Services\Exchange\Requests;

use App\Services\Exchange\Enums\SideEnum;
use App\Services\Exchange\Responses\SetLeverageResponseContract;

interface SetLeverageRequestContract
{
    public function setLeverage(string $symbol, SideEnum $side, string $leverage): SetLeverageResponseContract;
}
