<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    protected $model = Order::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id'      => User::inRandomOrder()->first()->id,
            'total_amount' => $this->faker->randomFloat(2, 50, 500),
            'status'       => $this->faker->randomElement(['pending', 'completed', 'cancelled']),
        ];
    }
}