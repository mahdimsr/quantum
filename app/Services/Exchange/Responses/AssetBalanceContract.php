<?php

namespace App\Services\Exchange\Responses;

use App\Services\Exchange\Repository\AssetCollection;

interface AssetBalanceContract
{
    public function data(): AssetCollection;
}
