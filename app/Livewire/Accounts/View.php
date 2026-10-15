<?php

namespace App\Livewire\Accounts;

use App\Models\User;
use Livewire\Component;

class View extends Component
{
    public User $user;
    public ?array $roles, $permissions;

    public function mount(User $user)
    {
        $this->user = User::find($user->id);
        $this->roles = $user->roles()->get()->toArray();
        $this->permissions = $user->permissions()->toArray();
    }

    public function render()
    {
        return view('livewire.accounts.view');
    }
}
