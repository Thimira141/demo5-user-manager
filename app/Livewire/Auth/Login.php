<?php

namespace App\Livewire\Auth;

use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts::guest')]
class Login extends Component
{
    public ?string $email;

    public ?string $password;

    /** @var array<string, array<int, ValidationRule|string>> */
    protected array $rules;

    /**
     * get validation rules from breeze built in
     *
     * @author Thimira Dilshan <thimirad865@gmail.com>
     */
    public function __construct()
    {
        $LoginRequestClass = new LoginRequest;
        $this->rules = $LoginRequestClass->rules();
    }

    public function render(): View
    {
        return view('livewire.auth.login');
    }

    public function login(): void
    {
        $this->validate();

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password])) {
            session()->regenerate();

            $this->redirect(route('dashboard'), navigate: true);

            return;
        }

        $this->addError('email', 'Invalid credentials.');
    }
}
