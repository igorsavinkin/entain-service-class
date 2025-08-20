<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class LoanFactory extends Factory
{
    public function definition()
    {
        return [
            'user_id' => $user = User::factory()->create(),
            'principal_amount' => $this->faker->numberBetween(1000, 100000),
            'interest_rate' => $this->faker->randomFloat(2, 1, 20),
            'loan_term_months' => $this->faker->numberBetween(12, 60),
            'start_date' => $this->faker->date(),
        ];
    }
}