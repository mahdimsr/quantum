<?php

namespace App\Services\Exchange\Repository;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class AssetCollection extends Collection
{
    public function ccy(string $ccy): Asset
    {
       return $this->filter(fn($item) => $item->getCcy() == Str::upper($ccy))->first();
    }
}
