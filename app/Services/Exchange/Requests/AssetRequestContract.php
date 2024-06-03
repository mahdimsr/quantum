<?php

namespace App\Services\Exchange\Requests;

use App\Services\Exchange\Responses\AssetBalanceContract;

interface AssetRequestContract
{
    public function futuresBalance(): ?AssetBalanceContract;
}
