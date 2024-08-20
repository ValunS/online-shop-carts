<?php

namespace Database\Factories;

use App\Models\Store;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Purchase>
 */
class PurchaseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $currencies = ['usd', 'eur', 'rub'];
        return [
            'store_id' => Store::inRandomOrder()->first(),
            'purchase_date' => $this->faker->date(),
            'sum' => $this->faker->randomFloat(2, 10, 1000),
            'currency' => $this->faker->randomElement($currencies),
            'document_path' => $this->faker->word() . '.pdf',
        ];
    }
}
