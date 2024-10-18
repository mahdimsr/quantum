<?php

namespace App\Services\Exchange\Responses;

use App\Services\Exchange\Repository\AssetCollection;

interface AssetBalanceContract
{
    public function message(): string;
    public function isSuccess(): bool;
    public function balance(): mixed;
}
