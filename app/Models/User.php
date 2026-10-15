<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Faker\Core\File;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable(['name', 'email', 'password', 'birthday', 'notes'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * avatar image or null
     * @author Thimira Dilshan <thimirad865@gmail.com>
     */
    public function getAvatar()
    {
        $path = (string) 'photos/' . $this->id . '.jpg';
        if (Storage::disk('public')->exists($path)) {
            return Storage::url($path);
        }
        return null;
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        $initials = Str::initials($this->name, true);

        return Str::length($initials) > 1
            ? Str::substr($initials, 0, 1) . Str::substr($initials, -1)
            : $initials;
    }

    /**
     * get user first name
     * @return string
     * @author Thimira Dilshan <thimirad865@gmail.com>
     */
    public function firstName(): string
    {
        return collect(explode(' ', trim($this->name)))
            ->filter()
            ->first() ?? '';
    }

    /**
     * get user roles
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     * @author Thimira Dilshan <thimirad865@gmail.com>
     */
    public function roles()
    {
        $RoleUser = new RoleUser();
        return $this->belongsToMany(Role::class, $RoleUser->getTable(), 'user_id', 'role_id');
    }

    /**
     * get user permissions
     * @return \Illuminate\Support\Collection<int, mixed>
     * @author Thimira Dilshan <thimirad865@gmail.com>
     */
    public function permissions()
    {
        return $this->roles()->with('permissions')->get()->pluck('permissions')->flatten()->unique('id');
    }
}
