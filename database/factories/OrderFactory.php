<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order_at' => now(),
            'note' => $this->faker->text(),
            'status' => ['Open', 'Done', 'Not Complete', 'On Progress'][rand(0,3)],
        ];
    }
}
