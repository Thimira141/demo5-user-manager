<?php

namespace App\Livewire\Auth\My;

use App\Models\User;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('My Profile')]
class View extends Component
{
    public User $user;

    /** @var array<array-key, mixed>|null */
    public ?array $roles;

    /** @var array<array-key, mixed>|null */
    public ?array $permissions;

    public function mount(): void
    {
        $user = Auth::user();
        if (! $user instanceof User) {
            throw new \LogicException('An authenticated user is required to view the profile.');
        }

        $this->user = $user;
        $this->roles = $this->user->roles()->get()->toArray();
        $this->permissions = $this->user->permissions()->toArray();
    }

    public function render(): ViewContract
    {
        return view('livewire.auth.my.view');
    }
}
