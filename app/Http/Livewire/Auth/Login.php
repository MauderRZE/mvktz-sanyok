<?php

namespace App\Http\Livewire\Auth;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;

#[Layout('layouts.auth')]
class Login extends Component
{
    public $login = '';
    public $password = '';
    public $remember = false;

    protected $rules = [
        'login' => 'required',
        'password' => 'required',
    ];

    public function submit()
    {
        $this->validate();

        if (Auth::attempt(['login' => $this->login, 'password' => $this->password], $this->remember)) {
            return redirect()->intended(route('admin.equipment'));
        }

        $this->addError('login', 'Невірний логін або пароль.');
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}
