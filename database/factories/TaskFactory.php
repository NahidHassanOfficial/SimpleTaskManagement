<?php

namespace Database\Factories;

use App\Enums\TaskSeverity;
use App\Enums\TaskStatus;
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
            'severity'    => fake()->randomElement(TaskSeverity::cases()),
            'status'      => fake()->randomElement(TaskStatus::cases()),
            'due_date'    => fake()->dateTimeBetween('now', '+1 month'),
            'assigned_to' => User::factory(),
            'created_by'  => User::factory(),
        ];
    }


    /**
     * Configure the model factory.
     */
    public function configure(): static
    {
        return $this->afterCreating(function (Task $task) {
            if (! $task->task_code) {
                $task->updateQuietly([
                    'task_code' => 'TASK-'.str_pad($task->id, 4, '0', STR_PAD_LEFT),
                ]);
            }
        });
    }
}