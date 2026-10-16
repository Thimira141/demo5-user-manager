<?php

namespace App\Models;

use Database\Factories\PermissionFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[Fillable(['name', 'description'])]
class Permission extends Model
{
    /** @use HasFactory<PermissionFactory> */
    use HasFactory;

    /**
     * roles belong to this permission
     *
     * @return BelongsToMany<Role, $this>
     *
     * @author Thimira Dilshan <thimirad865@gmail.com>
     */
    public function roles(): BelongsToMany
    {
        $PermissionRole = new PermissionRole;

        return $this->belongsToMany(Role::class, $PermissionRole->getTable(), 'permission_id', 'role_id');
    }
}
