<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ExchangeRate>
 */
class ExchangeRateFactory extends Factory
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
            'currency' => $this->faker->randomElement($currencies),
            'rate' => $this->faker->randomFloat(4, 30, 100),
        ];
    }
}
