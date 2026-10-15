<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts::guest')]
class Login extends Component
{
    public string $email = 'thimirad865@gmail.com', $password = '12345-passworD';

    protected array $rules;

    /**
     * get validation rules from breeze built in
     * @author Thimira Dilshan <thimirad865@gmail.com>
     */
    public function __construct()
    {
        $LoginRequestClass = new \App\Http\Requests\Auth\LoginRequest();
        $this->rules = $LoginRequestClass->rules();
    }


    public function render()
    {
        return view('livewire.auth.login');
    }

    public function login()
    {
        $this->validate();

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password])) {
            session()->regenerate();
            return $this->redirect(route('dashboard'), navigate: true);
        }

        $this->addError('email', 'Invalid credentials.');
    }
}
