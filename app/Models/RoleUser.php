<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\WithoutTimestamps;
use Illuminate\Database\Eloquent\Relations\Pivot;

#[WithoutTimestamps]
#[Fillable(["role_id", "user_id"])]
class RoleUser extends Pivot
{
    //
}
