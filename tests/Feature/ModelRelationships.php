<?php

namespace Tests\Feature;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ModelRelationships extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function role_permission_relationship()
    {
        // create role
        $role = Role::factory()->create(['name' => 'TestRole']);
        // create permission
        $permission = Permission::factory()->create(['name' => 'TEST_PERMISSION_1']);
        // sync permission with role
        $role->permissions()->sync([$permission->id]);
        // check data in relationship
        $this->assertTrue($role->permissions->contains($permission));
    }

    #[Test]
    public function role_user_relationship()
    {
        // create user
        $user = User::factory()->create(['name' => 'Test User']);
        // create role
        $role = Role::factory()->create(['name' => 'TestRole']);
        // sync role with user
        $user->roles()->sync([$role->id]);
        // check data in relationship
        $this->assertTrue($user->roles->contains($role));
    }

    #[Test]
    public function role_user_role_permission()
    {
        // create data
        $user = User::factory()->create(['name' => 'Test User']);
        $permission = Permission::factory()->create(['name' => 'TEST_PERMISSION_1']);
        $role = Role::factory()->create(['name' => 'TestRole']);

        // attach data to models
        $role->permissions()->sync([$permission->id]);
        $user->roles()->sync([$role->id]);

        // check data in db
        $this->assertDatabaseHas('users', ['id' => $user->id, 'name' => $user->name]);
        $this->assertDatabaseHas('roles', ['id' => $role->id, 'name' => $role->name]);
        $this->assertDatabaseHas('permissions', ['id' => $permission->id, 'name' => $permission->name]);

        // check data in models
        $this->assertTrue($user->roles->contains($role));
        $this->assertTrue($role->permissions->contains($permission));
        $this->assertCount(1, $user->permissions());
    }
}
