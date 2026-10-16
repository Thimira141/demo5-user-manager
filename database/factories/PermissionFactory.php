<?php

namespace Database\Factories;

use App\Models\Permission;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Permission>
 */
class PermissionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // data: $name = ["CREATE_USER", "DELETE_USER", "UPDATE_USER", "EXPORT_USER_DATA", "CREATE_ROLE", "DELETE_ROLE", "UPDATE_ROLE"]
        return [
            'name' => str(fake()->unique()->word())->upper(),
            'description' => fake()->text(50),
        ];
    }
}
