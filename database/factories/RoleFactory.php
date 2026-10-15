<?php

namespace Database\Factories;

use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Role>
 */
class RoleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // data $name = ["Admin", "Editor", "Teacher", "Student", "Employee", "Role-1", "Role-2"]
        return [
            "name" => str(fake()->unique()->word())->camel(),
            "description" => fake()->text(50)
        ];
    }
}
