<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Appointment>
 */
class AppointmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'customer_id' => \App\Models\User::factory(),
            'employee_id' => \App\Models\User::factory(),
            'service_id' => \App\Models\Service::factory(),
            'scheduled_at' => now()->addDay(),
            'end_at' => now()->addDay()->addMinutes(30),
            'final_price' => fake()->randomFloat(2, 20, 100),
            'status' => 'pending'
        ];
    }
}
