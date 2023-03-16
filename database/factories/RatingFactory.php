<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;


use \App\Models\Rating;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Rating>
 */
class RatingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = Rating::class;

    public function definition(): array
    {
        return [
            'notes' => $this->faker->text(),
            'point' => rand(1,5),
            'scale' => 5
        ];
    }
}
