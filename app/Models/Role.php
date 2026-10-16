<?php

namespace App\Models;

use Database\Factories\RoleFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['name', 'description'])]
class Role extends Model
{
    /** @use HasFactory<RoleFactory> */
    use HasFactory, SoftDeletes;

    /**
     * users belong to this role
     *
     * @return BelongsToMany<User, $this>
     *
     * @author Thimira Dilshan <thimirad865@gmail.com>
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'role_user', 'role_id', 'user_id');
    }

    /**
     * get permissions for this role
     *
     * @return BelongsToMany<Permission, $this>
     *
     * @author Thimira Dilshan <thimirad865@gmail.com>
     */
    public function permissions(): BelongsToMany
    {
        $PermissionRole = new PermissionRole;

        return $this->belongsToMany(Permission::class, $PermissionRole->getTable(), 'role_id', 'permission_id');
    }
}
