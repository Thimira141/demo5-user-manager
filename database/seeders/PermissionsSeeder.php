<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionsSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * permissions array
     * @var array
     * @version 1.0.0
     */
    protected $permissions = [
        ["name" => "CREATE_USER", "description" => "Create user"],
        ["name" => "DELETE_USER", "description" => "Delete user"],
        ["name" => "UPDATE_USER", "description" => "Update user"],
        ["name" => "EXPORT_USER_DATA", "description" => "Export user data"],
        ["name" => "CREATE_ROLE", "description" => "Create role"],
        ["name" => "DELETE_ROLE", "description" => "Delete role"],
        ["name" => "UPDATE_ROLE", "description" => "Update role"],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        collect($this->permissions)->each(fn ($permission) => Permission::updateOrCreate(["name"=> $permission["name"]], $permission));
    }
}
