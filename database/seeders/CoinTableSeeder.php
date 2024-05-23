<?php

namespace Database\Seeders;

use App\Models\Coin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Modules\CCXT\coinex;

class CoinTableSeeder extends Seeder
{
    public function run(): void
    {
        $coinex = new coinex();

        $marketData = $coinex->v2_public_get_futures_market()['data'];

        $marketDataAdapter = collect($marketData)->map(fn($item) => ['name' =>  $item['base_ccy'], 'percent_tolerance' => 1.00])->toArray();

        foreach ($marketDataAdapter as $marketDataItem) {

            Coin::query()->firstOrCreate($marketDataItem);
        }
    }
}
