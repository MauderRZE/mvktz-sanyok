<?php

namespace App\Http\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Login extends Component
{
    public $email = '';
    public $password = '';
    public $remember = false;

    protected $rules = [
        'email' => 'required|email',
        'password' => 'required',
    ];

    public function login()
    {
        $this->validate();

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            return redirect()->intended(route('admin.equipment'));
        }

        $this->addError('email', 'Невірний email або пароль.');
    }

    public function render()
    {
        return view('livewire.auth.login')->layout('layouts.auth');
    }
}
