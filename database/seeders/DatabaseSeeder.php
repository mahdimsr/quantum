<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->initialSuperUser();

        $this->call(CoinTableSeeder::class);

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }

    private function initialSuperUser(): void
    {
        User::query()->updateOrCreate([
            'email' => 'mahdi.msr4@gmail.com',],
            ['name' => 'mahdi msr',
                'telegram_chat_id' => '410300340',
                'password' => Hash::make('1376@')
            ]);
    }
}
