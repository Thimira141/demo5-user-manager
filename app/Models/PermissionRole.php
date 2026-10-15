<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\WithoutTimestamps;
use Illuminate\Database\Eloquent\Relations\Pivot;

#[WithoutTimestamps]
#[Fillable(["permission_id", "role_id"])]
class PermissionRole extends Pivot
{
    //
}
