<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(PermissionsSeeder::class);

        // admin create
        $admin = User::firstOrCreate(
            ['email' => 'admin@ex.com'],
            [
                'name' => 'Admin User',
                'password' => bcrypt('12345-passworD'),
            ],
        );
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);
        $adminRole->permissions()->sync(Permission::all()->pluck('id')->toArray());
        $admin->roles()->sync([$adminRole->id]);

        // user editor
        $userEditor = User::firstOrCreate(
            ['email' => 'editor@ex.com'],
            [
                'name' => 'User Editor',
                'password' => bcrypt('12345-passworD'),
            ],
        );
        $userEditorRole = Role::firstOrCreate(['name' => 'UserEditor']);
        $userEditorRole->permissions()->sync(Permission::whereIn('name', [
            'CREATE_USER',
            'DELETE_USER',
            'UPDATE_USER',
        ])->pluck('id')->toArray());
        $userEditor->roles()->sync([$userEditorRole->id]);

        // role editor
        $roleEditor = User::firstOrCreate(
            ['email' => 'role-editor@ex.com'],
            [
                'name' => 'RoleEditor',
                'password' => bcrypt('12345-passworD'),
            ],
        );
        $roleEditorRole = Role::firstOrCreate(['name' => 'RoleEditor']);
        $roleEditorRole->permissions()->sync(Permission::whereIn('name', [
            'CREATE_ROLE',
            'DELETE_ROLE',
            'UPDATE_ROLE',
        ])->pluck('id')->toArray());
        $roleEditor->roles()->sync([$roleEditorRole->id]);

        // basic user with no special permissions
        User::firstOrCreate(
            ['email' => 'basic@ex.com'],
            [
                'name' => 'Basic User',
                'password' => bcrypt('12345-passworD'),
            ],
        );
    }
}
