<?php

namespace Database\Seeders;

use App\Models\Coin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CoinTableSeeder extends Seeder
{
    public function run(): void
    {
        Coin::query()->updateOrCreate([
            'name' => 'BTC',
        ],[
            'percent_tolerance' => 0.1
        ]);

        Coin::query()->updateOrCreate([
            'name' => 'FTM',
        ],[
            'percent_tolerance' => 1.0
        ]);

        Coin::query()->updateOrCreate([
            'name' => 'TON',
        ],[
            'percent_tolerance' => 1.0
        ]);
    }
}
