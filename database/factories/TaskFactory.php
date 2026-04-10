<?php

namespace Database\Factories;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title'       => fake()->sentence(3),
            'description' => fake()->paragraph(),
            'severity'    => fake()->randomElement(['low', 'medium', 'high']),
            'status'      => fake()->randomElement(['pending', 'in progress', 'completed']),
            'due_date'    => fake()->dateTimeBetween('now', '+1 month'),
            'assigned_to' => User::pluck('id')->random(),
            'created_by'  => User::pluck('id')->random(),
        ];
    }
}