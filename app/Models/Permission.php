<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(["name", "description"])]
class Permission extends Model
{
    /** @use HasFactory<\Database\Factories\PermissionFactory> */
    use HasFactory;

    /**
     * roles belong to this permission
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     * @author Thimira Dilshan <thimirad865@gmail.com>
     */
    public function roles()
    {
        $PermissionRole = new PermissionRole();
        return $this->belongsToMany(Role::class, $PermissionRole->getTable(), 'permission_id', 'role_id');
    }
}
