<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use \App\Models\Merchant;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Merchant>
 */
class MerchantFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = Merchant::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->company(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' =>  password_hash('password', PASSWORD_BCRYPT, ['cost' => 10]), // password
            'address1' => $this->faker->address(),
            'address2' => $this->faker->address(),
            'is_branch' => false,
            'phone_number' => $this->faker->phoneNumber(),
        ];
    }
}
