<?php

namespace App\Services\Exchange\Repository;

use Illuminate\Support\Collection;

class PositionLevelCollection extends Collection
{
    public function __construct($items = []) {

        $this->items = collect($items)->map(fn ($item) => PositionLevel::fromArray($item))->toArray();
    }
}
