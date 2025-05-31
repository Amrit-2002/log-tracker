<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
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
            'client' => $this->faker->company,
            'task_bug_name' => $this->faker->sentence(3),
            'owner' => $this->faker->name,
            'priority' => $this->faker->randomElement(['Low', 'Medium', 'High']),
            'start_date' => $this->faker->date(),
            'end_date' => $this->faker->date(),
            'dev_status' => $this->faker->randomElement(['New','In-progress','Completed','On-hold','NA']),
            'unit_test_status' => $this->faker->randomElement(['New','In-progress','Completed','On-hold','NA']),
            'staging_status' => $this->faker->randomElement(['New','In-progress','Completed','On-hold','NA']),
            'prod_status' => $this->faker->randomElement(['New','In-progress','Completed','On-hold','NA']),
            'comment' => $this->faker->paragraph,
            'user_id' => User::inRandomOrder()->first()->id,
        ];
    }
}
