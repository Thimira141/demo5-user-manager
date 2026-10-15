<?php

namespace Tests\Feature;

use App\Http\Middleware\PermissionCheck;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\TestCase;

class PermissionMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function permission_middleware_allows_users_with_any_required_permission(): void
    {
        $user = $this->userWithPermissions(['UPDATE_ROLE']);
        $this->actingAs($user);

        $response = (new PermissionCheck())->handle(
            Request::create('/roles', 'GET'),
            fn() => response('ok'),
            'CREATE_ROLE,DELETE_ROLE,UPDATE_ROLE',
        );

        $this->assertSame(200, $response->getStatusCode());
    }

    #[Test]
    public function permission_middleware_rejects_users_without_any_required_permission(): void
    {
        $user = $this->userWithPermissions(['CREATE_USER']);
        $this->actingAs($user);

        $this->expectException(HttpException::class);

        (new PermissionCheck())->handle(
            Request::create('/roles', 'GET'),
            fn() => response('ok'),
            'CREATE_ROLE,DELETE_ROLE,UPDATE_ROLE',
        );
    }

    /** @param array<int, string> $permissionNames */
    private function userWithPermissions(array $permissionNames): User
    {
        $user = User::factory()->create();
        $role = Role::factory()->create();
        $permissions = collect($permissionNames)
            ->map(fn(string $name) => Permission::factory()->create(['name' => $name]));

        $role->permissions()->sync($permissions->pluck('id'));
        $user->roles()->sync([$role->id]);

        return $user;
    }
}
