<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\ExchangeRate;
use App\Models\Purchase;
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
        ExchangeRate::create(['currency' => 'USD', 'rate' => 88]);
        ExchangeRate::create(['currency' => 'EUR', 'rate' => 100]);
        ExchangeRate::create(['currency' => 'RUB', 'rate' => 1]);

        Store::factory()->count(3)->create();

        Purchase::factory()->count(10)->create();
    }
}