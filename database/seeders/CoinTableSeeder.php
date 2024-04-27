<?php

namespace Database\Seeders;

use App\Models\Coin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CoinTableSeeder extends Seeder
{
    public function run(): void
    {
        Coin::query()->create([
            'name' => 'BTC',
            'percent_tolerance' => 0.1
        ]);

        Coin::query()->create([
            'name' => 'FTM',
            'percent_tolerance' => 1.0
        ]);

        Coin::query()->create([
            'name' => 'TON',
            'percent_tolerance' => 1.0
        ]);
    }
}
