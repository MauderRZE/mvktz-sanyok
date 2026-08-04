<?php

namespace App\Http\Livewire\Admin;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class UserManager extends Component
{
    use WithPagination;

    public $userId;

    public $name;

    public $login;

    public $password;

    public $isOpen = 0;

    public $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = User::when($this->search, function ($q) {
            $search = '%'.$this->search.'%';
            $q->where(function ($sub) use ($search) {
                $sub->where('name', 'like', $search)
                    ->orWhere('login', 'like', $search);
            });
        })
            ->orderBy('id', 'desc');

        return view('livewire.admin.user-manager', [
            'users' => $query->paginate(15),
        ]);
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->resetPage();
    }

    public function create()
    {
        $this->resetInputFields();
        $this->openModal();
    }

    public function openModal()
    {
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }

    private function resetInputFields()
    {
        $this->userId = null;
        $this->name = '';
        $this->login = '';
        $this->password = '';
    }

    public function store()
    {
        $rules = [
            'name' => 'required',
            'login' => 'required|unique:users,login,'.$this->userId,
        ];

        // Якщо це новий користувач або пароль був введений, вимагаємо пароль
        if (! $this->userId || ! empty($this->password)) {
            $rules['password'] = 'required|min:6';
        }

        $this->validate($rules);

        $data = [
            'name' => $this->name,
            'login' => $this->login,
        ];

        if (! empty($this->password)) {
            $data['password'] = Hash::make($this->password);
        }

        User::updateOrCreate(['id' => $this->userId], $data);

        session()->flash('message',
            $this->userId ? 'Користувача оновлено.' : 'Користувача створено.');

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $this->userId = $id;
        $this->name = $user->name;
        $this->login = $user->login;
        $this->password = ''; // Не заповнюємо пароль
        $this->openModal();
    }

    public function delete($id)
    {
        if (auth()->id() == $id) {
            session()->flash('message', 'Ви не можете видалити самі себе!');

            return;
        }

        User::find($id)->delete();
        session()->flash('message', 'Користувача видалено.');
    }
}
