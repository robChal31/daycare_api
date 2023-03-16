<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employe>
 */
class EmployeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $edu = ['SMA', 'SMK', 'D3', 'S1'];
        return [
            'name' => $this->faker->company(),
            'username' => $this->faker->userName(),
            'password' =>  password_hash('password', PASSWORD_BCRYPT, ['cost' => 10]), // password
            'position_id' => $this->faker->uuid(),
            'age' => rand(20,50),// password
            'phone_number' => $this->faker->phoneNumber(),
            'address' => $this->faker->address(),
            'education' => $edu[rand(0,3)],
        ];
    }
}
