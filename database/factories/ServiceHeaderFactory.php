<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use \App\Models\ServiceHeader;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Service_Header>
 */
class ServiceHeaderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = ServiceHeader::class;

    public function definition(): array
    {
        $services = ['Childcare', 'Education', 'Enrichment activities', 'Nutritious meals and snacks', 'Health and safety', 'Parent support', 'Other', 'Toddler', 'Special Need', 'Keeper'];
        return [
            'name' => $services[rand(0,9)],
            'desc' => $this->faker->text(),
        ];
    }
}
