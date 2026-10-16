<?php

namespace App\Livewire\Auth;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use TallStackUi\Traits\Interactions;

class Logout extends Component
{
    use Interactions;

    /**
     * init logout ad confirm dialog
     *
     * @author Thimira Dilshan <thimirad865@gmail.com>
     */
    public function logout(): void
    {
        $this->dialog()
            ->question('Logout!', 'Are you sure to logout from the system?')
            ->confirm('Log out', 'confirmLogout')
            ->cancel('Cancel')
            ->send();
    }

    /**
     * logout logic
     *
     * @author Thimira Dilshan <thimirad865@gmail.com>
     */
    public function confirmLogout(): void
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();

        $this->redirect(route('login'), navigate: true);
    }

    public function render(): View
    {
        return view('livewire.auth.logout');
    }
}
