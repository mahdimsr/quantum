<?php

namespace App\Services\Exchange\Repository;

use Illuminate\Support\Str;

class Target
{
    private string $type;
    private mixed $stopPrice;
    private ?string $workingType;
    private mixed $price;

    private function __construct()
    {
    }

    public static function create(string $type, mixed $stopPrice, mixed $price, ?string $workingType = null): self
    {
        $target = new self();

        $target->type = $type;
        $target->stopPrice = $stopPrice;
        $target->workingType = $workingType;
        $target->price = $price;

        return $target;
    }

    public function toArray(): array
    {
        return [
            'type' => $this->type,
            'stopPrice' => $this->stopPrice,
            'workingType' => Str::of($this->workingType)->upper()->toString(),
            'price' => $this->price,
        ];
    }
}
