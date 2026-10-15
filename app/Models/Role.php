<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(["name", "description"])]
class Role extends Model
{
    /** @use HasFactory<\Database\Factories\RoleFactory> */
    use HasFactory, SoftDeletes;

    /**
     * users belong to this role
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     * @author Thimira Dilshan <thimirad865@gmail.com>
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'role_user', 'role_id', 'user_id');
    }

    /**
     * get permissions for this role
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     * @author Thimira Dilshan <thimirad865@gmail.com>
     */
    public function permissions()
    {
        $PermissionRole = new PermissionRole();
        return $this->belongsToMany(Permission::class, $PermissionRole->getTable(), 'role_id', 'permission_id');
    }
}
