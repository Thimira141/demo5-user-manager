<?php

namespace App\Livewire\Accounts;

use App\Models\User;
use Illuminate\Contracts\View\View as ViewContract;
use Livewire\Component;

class View extends Component
{
    public User $user;

    /** @var array<array-key, mixed>|null */
    public ?array $roles;

    /** @var array<array-key, mixed>|null */
    public ?array $permissions;

    public function mount(User $user): void
    {
        $this->user = $user;
        $this->roles = $user->roles()->get()->toArray();
        $this->permissions = $user->permissions()->toArray();
    }

    public function render(): ViewContract
    {
        return view('livewire.accounts.view');
    }
}
