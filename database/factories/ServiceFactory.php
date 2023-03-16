<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use \App\Models\Service;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Service>
 */
class ServiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = Service::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->catchPhrase(),
            'desc' => $this->faker->text(),
            'price' => rand(100000, 990000),
            'image_path' =>$this->faker->imageUrl(640, 480, 'animals', true),
        ];
    }
}
