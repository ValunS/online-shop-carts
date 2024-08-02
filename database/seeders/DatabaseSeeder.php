<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\ExchangeRate;
use App\Models\Purshase;
use App\Models\Store;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        ExchangeRate::create(['currency' => 'usd', 'rate' => 88]);
        ExchangeRate::create(['currency' => 'eur', 'rate' => 100]);
        ExchangeRate::create(['currency' => 'rub', 'rate' => 1]);

        Store::factory()->count(20)->create();

        Purshase::factory()->count(100)->create();
    }
}
