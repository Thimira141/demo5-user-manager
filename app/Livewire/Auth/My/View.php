<?php

namespace App\Livewire\Auth\My;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title("My Profile")]
class View extends Component
{
    public User $user;
    public ?array $roles, $permissions;

    public function mount()
    {
        $this->user = Auth::user();
        $this->roles = $this->user->roles()->get()->toArray();
        $this->permissions = $this->user->permissions()->toArray();
    }

    public function render()
    {
        return view('livewire.auth.my.view');
    }
}
