<?php

namespace App\Services\Exchange\Repository;

class Coin
{
    private string $name;

    private function __construct()
    {
    }

    public static function create(string $name): Coin
    {
        $coin = new self();

        $coin->name = $name;

        return $coin;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
