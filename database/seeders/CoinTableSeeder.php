<?php

namespace Database\Seeders;

use App\Models\Coin;
use App\Services\Exchange\Facade\Exchange;
use App\Services\Exchange\Repository\Coin as CoinRepository;
use Illuminate\Database\Seeder;

class CoinTableSeeder extends Seeder
{
    public function run(): void
    {
        $coinsResponse = Exchange::coins();

        if ($coinsResponse->data()->isNotEmpty()) {

            $coinsResponse->data()->each(function(CoinRepository $coinRepository) {

                Coin::query()->updateOrCreate(['name' => $coinRepository->getName()]);
            });
        }
    }
}
