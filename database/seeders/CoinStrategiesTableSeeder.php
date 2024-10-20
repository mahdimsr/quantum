<?php

namespace Database\Seeders;

use App\Enums\StrategyEnum;
use App\Models\Coin;
use Illuminate\Database\Seeder;

class CoinStrategiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $coins = Coin::all();

        foreach ($coins as $coin) {

            $coin->strategies()->firstOrCreate([
                'name' => StrategyEnum::Static_Profit->value
            ]);
        }
    }
}
